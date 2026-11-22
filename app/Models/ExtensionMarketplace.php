<?php
declare(strict_types=1);

/**
 * @phpstan-import-type CatalogEntry from FreshRSS_ExtensionCatalog
 * @phpstan-type Package array{slug:string,directory:string,name:string,entrypoint:string,type:'user'|'system',
 *   version:string,minimum:string,commit:string,sha256:string}
 * @phpstan-type Receipt array{id:string,activation:string,version:string,tree:string}
 */
final class FreshRSS_ExtensionMarketplace {
	public const WORK_DIRECTORY = '.marketplace';
	private const RECEIPT = '.freshrss-marketplace.json';
	private const MAX_ARCHIVE = 8388608;
	private const MAX_EXTRACTED = 16777216;
	private const MAX_FILES = 4096;

	/** @var list<Package> */
	private const PACKAGES = [
		[
			'slug' => 'FreshRSS/Extensions',
			'directory' => 'xExtension-ColorfulList',
			'name' => 'Colorful List',
			'entrypoint' => 'ColorfulList',
			'type' => 'user',
			'version' => '0.3.3',
			'minimum' => '1.28.0',
			'commit' => '5c7bc46e5485433925c45b224471d6e874ed7dee',
			'sha256' => 'e653022f97171d810f20ba4d264cf0d92272d3ad303ec36929ca2ceac7bc48a8',
		],
		[
			'slug' => 'FreshRSS/Extensions',
			'directory' => 'xExtension-ColorfulList',
			'name' => 'Colorful List',
			'entrypoint' => 'ColorfulList',
			'type' => 'user',
			'version' => '0.3.2',
			'minimum' => '1.28.0',
			'commit' => '4ac7c9c6a6c0ba4ad1389747f666fdd7ba9937fb',
			'sha256' => 'afcceee03554ed75411f238bb873499d3f7bfc3addd31b75dae282af2f5a2723',
		],
		[
			'slug' => 'aledeg/xExtension-DateFormat',
			'directory' => '.',
			'name' => 'Date Format',
			'entrypoint' => 'DateFormat',
			'type' => 'user',
			'version' => '0.2.2',
			'minimum' => '1.28.0',
			'commit' => '594647768a83516d0c8ba05bc3616dcb5def08ea',
			'sha256' => '4c6ca82d65705618f3b879e43115b770308604fe9bc48870911994e25229deb2',
		],
		[
			'slug' => 'aledeg/xExtension-DateFormat',
			'directory' => '.',
			'name' => 'Date Format',
			'entrypoint' => 'DateFormat',
			'type' => 'user',
			'version' => '0.2.1',
			'minimum' => '1.28.0',
			'commit' => '3265466644f178de57a3489b9b4280aae938f635',
			'sha256' => '63aef07e908b43b3c6093765acee6bcf8a69ee1d7d18033e093f15c893eda462',
		],
	];

	/** @param CatalogEntry $entry
	 * @return Package|null
	 */
	private static function package(array $entry): ?array {
		foreach (self::PACKAGES as $package) {
			if (FreshRSS_ExtensionCatalog::repository($entry['url']) === 'github.com/' . strtolower($package['slug']) &&
				$entry['method'] === 'git' && $entry['directory'] === $package['directory'] &&
				$entry['name'] === $package['name'] && $entry['entrypoint'] === $package['entrypoint'] &&
				$entry['type'] === $package['type'] && $entry['version'] === $package['version']) {
				return $package;
			}
		}
		return null;
	}

	/** @param CatalogEntry $entry
	 * @param list<CatalogEntry> $catalog
	 * @param array<string,array<array-key,mixed>>|null $inventory
	 * @return array{action:string,reason:string,version:string}
	 */
	public static function status(array $entry, array $catalog, ?array $inventory = null): array {
		$matches = self::localMatches($entry, $inventory ?? self::inventory());
		$path = self::destination($entry['entrypoint']);
		$version = $matches[$path] ?? '';
		$receipt = self::receipt($path);
		$package = self::package($entry);
		$catalogMatches = array_filter($catalog, static fn (array $item): bool =>
			strcasecmp($entry['entrypoint'], $item['entrypoint']) === 0 || $entry['name'] === $item['name']);

		$reason = match (true) {
			count($catalogMatches) !== 1, count($matches) > 1 => 'ambiguous',
			$version === '' && isset($matches[$path]) && self::metadata($path . '/metadata.json') === [] => 'occupied',
			!empty($matches) && ($version === '' || $receipt === null ||
				$receipt['id'] !== FreshRSS_ExtensionCatalog::id($entry) || $receipt['version'] !== $version) => 'origin',
			!FreshRSS_ExtensionCatalog::compatible($entry) => 'incompatible',
			$version !== '' && version_compare($version, $entry['version'], '>=') => '',
			$package === null => 'unsupported',
			version_compare(FRESHRSS_VERSION, $package['minimum'], '<') => 'incompatible',
			!self::canWrite() => 'read_only',
			$receipt !== null && !self::matchesReceipt($path, $receipt) => 'origin',
			default => '',
		};
		$action = $reason === '' && $version === '' ? 'install' : '';
		if ($reason === '' && $version !== '' && version_compare($version, $entry['version'], '<')) {
			$action = 'upgrade';
		}
		return ['action' => $action, 'reason' => $reason, 'version' => $version];
	}

	private static function destination(string $entrypoint): string {
		return THIRDPARTY_EXTENSIONS_PATH . '/xExtension-' . $entrypoint;
	}

	/** @return array<string,array<array-key,mixed>> */
	public static function inventory(): array {
		$inventory = [];
		foreach ([CORE_EXTENSIONS_PATH, THIRDPARTY_EXTENSIONS_PATH] as $root) {
			foreach (scandir($root) ?: [] as $directory) {
				if (in_array($directory, ['.', '..', self::WORK_DIRECTORY], true)) {
					continue;
				}
				$path = $root . '/' . $directory;
				$inventory[$path] = self::metadata($path . '/metadata.json');
			}
		}
		return $inventory;
	}

	/** @param CatalogEntry $entry
	 * @param array<string,array<array-key,mixed>> $inventory
	 * @return array<string,string>
	 */
	private static function localMatches(array $entry, array $inventory): array {
		$matches = [];
		foreach ($inventory as $path => $metadata) {
			$entrypoint = $metadata['entrypoint'] ?? null;
			if (strcasecmp(basename($path), basename(self::destination($entry['entrypoint']))) === 0 ||
				(is_string($entrypoint) && strcasecmp($entrypoint, $entry['entrypoint']) === 0) ||
				($metadata['name'] ?? null) === $entry['name']) {
				$matches[$path] = is_string($metadata['version'] ?? null) ? $metadata['version'] : '';
			}
		}
		return $matches;
	}

	private static function canWrite(): bool {
		$root = realpath(THIRDPARTY_EXTENSIONS_PATH);
		$core = realpath(CORE_EXTENSIONS_PATH);
		return $root !== false && $core !== false && !str_starts_with($root . '/', $core . '/') &&
			is_writable(THIRDPARTY_EXTENSIONS_PATH);
	}

	/** @return array<array-key,mixed> */
	private static function metadata(string $file): array {
		if (is_link($file) || !is_file($file)) {
			return [];
		}
		$json = json_decode(@file_get_contents($file, length: 65537) ?: '', false, 16);
		return $json instanceof stdClass ? get_object_vars($json) : [];
	}

	/** @return Receipt|null */
	private static function receipt(string $path): ?array {
		if (is_link($path) || dirname($path) !== THIRDPARTY_EXTENSIONS_PATH) {
			return null;
		}
		$meta = self::metadata($path . '/' . self::RECEIPT);
		if (!is_string($meta['id'] ?? null) || !is_string($meta['version'] ?? null) || !is_string($meta['tree'] ?? null) ||
			!is_string($meta['activation'] ?? null) || preg_match('/^[a-f0-9]{32}$/D', $meta['activation']) !== 1) {
			return null;
		}
		return ['id' => $meta['id'], 'version' => $meta['version'], 'tree' => $meta['tree'], 'activation' => $meta['activation']];
	}

	public static function activationKey(Minz_Extension $extension): string {
		$receipt = self::receipt($extension->getPath());
		return $receipt === null ? $extension->getName() : 'marketplace:' . $receipt['activation'];
	}

	public static function canRemove(Minz_Extension $extension): bool {
		$path = $extension->getPath();
		$target = realpath($path);
		$core = realpath(CORE_EXTENSIONS_PATH);
		$data = realpath(DATA_PATH);
		return dirname($path) === THIRDPARTY_EXTENSIONS_PATH && !is_link($path) && is_dir($path) &&
			$target !== false && $core !== false && $data !== false &&
			!str_starts_with($core . '/', $target . '/') && !str_starts_with($target . '/', $core . '/') &&
			!str_starts_with($data . '/', $target . '/') &&
			self::canWrite();
	}

	/**
	 * Lock mutations and startup scans so the manager never sees an update halfway through publication.
	 *
	 * @template T
	 * @param Closure(string):T $operation
	 * @return T
	 * @throws RuntimeException
	 */
	public static function locked(Closure $operation, bool $write = false): mixed {
		$work = THIRDPARTY_EXTENSIONS_PATH . '/' . self::WORK_DIRECTORY;
		if ($write && (!self::canWrite() ||
			(!is_dir($work) && !@mkdir($work, 0700) && !is_dir($work)))) {
			throw new RuntimeException('read_only');
		}
		if (is_link($work) || is_link($work . '/lock')) {
			if ($write) {
				throw new RuntimeException('occupied');
			}
			return $operation($work);
		}
		$lock = $write ? @fopen($work . '/lock', 'c') : @fopen($work . '/lock', 'r');
		if (!$write && $lock === false && is_file($work . '/lock')) {
			$lock = @fopen($work . '/lock', 'r');
		}
		if ($lock === false) {
			if ($write || is_file($work . '/lock')) {
				throw new RuntimeException('read_only');
			}
			return $operation($work);
		}
		try {
			if (!flock($lock, $write ? LOCK_EX : LOCK_SH)) {
				throw new RuntimeException('Marketplace lock failed');
			}
			clearstatcache();
			if ($write || is_dir($work . '/backup')) {
				if (!$write && !flock($lock, LOCK_EX)) {
					throw new RuntimeException('Marketplace recovery lock failed');
				}
				try {
					self::recover($work);
				} catch (RuntimeException $e) {
					if ($write) {
						throw $e;
					}
					Minz_Log::warning('Marketplace recovery pending: ' . $e->getMessage());
				}
			}
			return $operation($work);
		} finally {
			fclose($lock);
		}
	}

	/** @throws RuntimeException */
	private static function recover(string $work): void {
		$backup = $work . '/backup';
		if (!is_dir($backup)) {
			return;
		}
		foreach (scandir($backup) ?: [] as $directory) {
			if (preg_match('/^xExtension-[a-z0-9_]+$/iD', $directory) !== 1) {
				continue;
			}
			$path = THIRDPARTY_EXTENSIONS_PATH . '/' . $directory;
			$previous = $backup . '/' . $directory;
			if (!file_exists($path) && !is_link($path)) {
				if (!@rename($previous, $path)) {
					throw new RuntimeException('Marketplace rollback failed: ' . $directory);
				}
				continue;
			}

			// A manual copy may have appeared while the old version was in the backup.
			$old = self::metadata($previous . '/' . self::RECEIPT);
			$new = self::receipt($path);
			if ($new === null || $new['id'] !== ($old['id'] ?? null) ||
				$new['activation'] !== ($old['activation'] ?? null) || !is_string($old['version'] ?? null) ||
				!version_compare($new['version'], $old['version'], '>')) {
				throw new RuntimeException('occupied');
			}
		}
		if (is_writable($work)) {
			// Detach the backup before deleting files. A partial cleanup is never used for recovery.
			$retired = $work . '/retired';
			self::deleteTree($retired);
			if (!@rename($backup, $retired)) {
				throw new RuntimeException('Cannot retire extension backup');
			}
			self::deleteTree($retired);
		}
	}

	/** @param CatalogEntry $entry
	 * @param list<CatalogEntry> $catalog
	 * @throws RuntimeException
	 */
	public static function apply(array $entry, array $catalog, string $action): void {
		self::locked(static function (string $work) use ($entry, $catalog, $action): void {
			clearstatcache();
			$status = self::status($entry, $catalog);
			$package = self::package($entry);
			if (!in_array($action, ['install', 'upgrade'], true) || $status['action'] !== $action || $package === null) {
				throw new RuntimeException($status['reason'] ?: 'changed');
			}
			$destination = self::destination($entry['entrypoint']);
			$receipt = self::receipt($destination);
			$stage = $work . '/stage';
			self::deleteTree($stage);
			if (!mkdir($stage, 0700) || !mkdir($stage . '/package', 0755)) {
				throw new RuntimeException('Cannot prepare extension staging directory');
			}
			try {
				self::stage($package, $stage);
				$stagedPackage = $stage . '/package';
				$record = json_encode([
					'id' => FreshRSS_ExtensionCatalog::id($entry),
					'version' => $entry['version'],
					// Reinstalling starts with a fresh activation key for every user.
					'activation' => $receipt['activation'] ?? bin2hex(random_bytes(16)),
					'tree' => self::treeHash($stagedPackage),
				], JSON_THROW_ON_ERROR);
				if (file_put_contents($stagedPackage . '/' . self::RECEIPT, $record) !== strlen($record)) {
					throw new RuntimeException('Cannot record package provenance');
				}

				if ($action === 'upgrade') {
					$backup = $work . '/backup';
					if (!mkdir($backup, 0700) || !@rename($destination, $backup . '/' . basename($destination))) {
						throw new RuntimeException('Cannot back up installed extension');
					}
				}
				try {
					clearstatcache();
					if (file_exists($destination) || is_link($destination) || !@rename($stagedPackage, $destination)) {
						throw new RuntimeException('Cannot publish extension');
					}
				} finally {
					self::recover($work);
				}
			} finally {
				self::deleteTree($stage);
			}
		}, true);
	}

	/** @throws RuntimeException */
	public static function remove(Minz_Extension $extension): void {
		self::locked(static function (string $work) use ($extension): void {
			clearstatcache();
			$path = $extension->getPath();
			$meta = self::metadata($path . '/metadata.json');
			if (!self::canRemove($extension) || ($meta['name'] ?? null) !== $extension->getName() ||
				($meta['entrypoint'] ?? null) !== $extension->getEntrypoint() ||
				self::activationKey($extension) !== Minz_ExtensionManager::activationKey($extension)) {
				throw new RuntimeException('changed');
			}
			$trash = $work . '/removed';
			self::deleteTree($trash);
			if (!@rename($path, $trash)) {
				throw new RuntimeException('Cannot remove extension');
			}
			self::deleteTree($trash);
		}, true);
	}

	/** @param Package $package */
	private static function stage(array $package, string $stage): void {
		$url = 'https://codeload.github.com/' . $package['slug'] . '/zip/' . $package['commit'];
		$download = FreshRSS_ExtensionCatalog::download($url, self::MAX_ARCHIVE);
		if (hash('sha256', $download) !== $package['sha256'] ||
			file_put_contents($stage . '/download.zip', $download) !== strlen($download)) {
			throw new RuntimeException('Package checksum or staging failed');
		}

		$zip = new ZipArchive();
		if ($zip->open($stage . '/download.zip', ZipArchive::CHECKCONS) !== true) {
			throw new RuntimeException('Invalid extension ZIP archive');
		}
		try {
			self::extract($zip, $package, $stage . '/package');
		} finally {
			$zip->close();
		}

		$meta = self::metadata($stage . '/package/metadata.json');
		$minimum = $meta['compatibility'] ?? $package['minimum'];
		if (($meta['name'] ?? null) !== $package['name'] || ($meta['entrypoint'] ?? null) !== $package['entrypoint'] ||
			($meta['version'] ?? null) !== $package['version'] || ($meta['type'] ?? null) !== $package['type'] ||
			!is_string($minimum) || !version_compare(FRESHRSS_VERSION, $minimum, '>=') ||
			!is_file($stage . '/package/extension.php')) {
			throw new RuntimeException('Package metadata does not match the approved version');
		}
	}

	/** @param Package $package */
	private static function extract(ZipArchive $zip, array $package, string $directory): void {
		if ($zip->numFiles > self::MAX_FILES) {
			throw new RuntimeException('Package contains too many files');
		}
		$prefix = basename($package['slug']) . '-' . $package['commit'] . '/';
		if ($package['directory'] !== '.') {
			$prefix .= $package['directory'] . '/';
		}
		$total = 0;
		for ($i = 0; $i < $zip->numFiles; ++$i) {
			$stat = $zip->statIndex($i);
			if ($stat === false || !$zip->getExternalAttributesIndex($i, $opsys, $attributes) || !is_int($attributes)) {
				throw new RuntimeException('Cannot read package archive entry');
			}
			$name = $stat['name'];
			$isDirectory = str_ends_with($name, '/');
			$mode = ($attributes >> 16) & 0170000;
			if (strlen($name) > 512 || preg_match('~^(?:[a-zA-Z0-9_.+@ -]+/)*[a-zA-Z0-9_.+@ -]+/?$~D', $name) !== 1 ||
				preg_match('~(?:^|/)\.{1,2}(?:/|$)~', $name) === 1 ||
				!in_array($mode, [0, $isDirectory ? 0040000 : 0100000], true)) {
				throw new RuntimeException('Unsafe package archive entry');
			}
			if ($isDirectory || !str_starts_with($name, $prefix)) {
				continue;
			}
			$relative = substr($name, strlen($prefix));
			$total += $stat['size'];
			if ($total > self::MAX_EXTRACTED || $relative === self::RECEIPT) {
				throw new RuntimeException('Package extraction limit or reserved path');
			}
			$target = $directory . '/' . $relative;
			$parent = dirname($target);
			if (file_exists($target) || (!is_dir($parent) && !mkdir($parent, 0755, true))) {
				throw new RuntimeException('Conflicting package archive entries');
			}
			$content = $zip->getFromIndex($i, $stat['size'] + 1);
			if ($content === false || strlen($content) !== $stat['size'] ||
				hash('crc32b', $content) !== sprintf('%08x', $stat['crc']) ||
				file_put_contents($target, $content) !== $stat['size']) {
				throw new RuntimeException('Incomplete package archive entry');
			}
		}
	}

	/** @param Receipt $receipt */
	private static function matchesReceipt(string $path, array $receipt): bool {
		try {
			return self::treeHash($path) === $receipt['tree'];
		} catch (RuntimeException) {
			return false;
		}
	}

	/** @throws RuntimeException */
	private static function treeHash(string $path): string {
		$files = [];
		$bytes = 0;
		/** @var RecursiveIteratorIterator<RecursiveDirectoryIterator> $iterator */
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
		foreach ($iterator as $file) {
			/** @var SplFileInfo $file */
			$name = substr($file->getPathname(), strlen($path) + 1);
			if ($name === self::RECEIPT) {
				continue;
			}
			$size = $file->getSize();
			if ($file->isLink() || !$file->isFile() || $size === false ||
				($bytes += $size) > self::MAX_EXTRACTED || count($files) >= self::MAX_FILES) {
				throw new RuntimeException('origin');
			}
			$hash = hash_file('sha256', $file->getPathname());
			if ($hash === false) {
				throw new RuntimeException('origin');
			}
			$files[$name] = $hash;
		}
		ksort($files, SORT_STRING);
		return hash('sha256', serialize($files));
	}

	private static function deleteTree(string $path): void {
		if (is_link($path) || is_file($path)) {
			@unlink($path);
		} elseif (is_dir($path)) {
			foreach (array_diff(scandir($path) ?: [], ['.', '..']) as $name) {
				self::deleteTree($path . '/' . $name);
			}
			@rmdir($path);
		}
	}
}
