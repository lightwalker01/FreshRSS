<?php
declare(strict_types=1);

/**
 * @phpstan-type CatalogEntry array{name:string,entrypoint:string,author:string,description:string,version:string,type:'system'|'user',url:string,method:string,directory:string,compatibility:string}
 */
final class FreshRSS_ExtensionCatalog {
	public const OFFICIAL_REPOSITORY = 'github.com/freshrss/extensions';
	private const URL = 'https://raw.githubusercontent.com/FreshRSS/Extensions/refs/heads/main/extensions.json';
	private const MAX_BYTES = 1048576;

	/**
	 * @return array{list<CatalogEntry>, bool} Catalog and whether a refresh succeeded.
	 */
	public static function get(bool $force = false): array {
		if (!FreshRSS_Context::userConf()->retrieve_extension_list) {
			return [[], false];
		}

		$cache = CACHE_PATH . '/extension_list.json';
		$cached = self::parse(@file_get_contents($cache, length: self::MAX_BYTES + 1) ?: '');
		if (!$force && $cached !== null && (@filemtime($cache) ?: 0) > time() - 86400) {
			return [$cached, false];
		}

		try {
			$json = self::download(self::URL, self::MAX_BYTES);
			$list = self::parse($json);
			if ($list === null) {
				throw new RuntimeException('Invalid extension catalog');
			}
			$tmp = @tempnam(CACHE_PATH, 'catalog-');
			if ($tmp === false) {
				throw new RuntimeException('Cannot stage extension catalog');
			}
			try {
				if (dirname($tmp) !== realpath(CACHE_PATH) ||
					file_put_contents($tmp, $json) !== strlen($json) || !rename($tmp, $cache)) {
					throw new RuntimeException('Cannot save extension catalog');
				}
			} finally {
				if (is_file($tmp)) {
					unlink($tmp);
				}
			}
			return [$list, true];
		} catch (RuntimeException $e) {
			Minz_Log::warning($e->getMessage());
			return [$cached ?? [], false];
		}
	}

	/** @return list<CatalogEntry>|null */
	public static function parse(string $json): ?array {
		if (strlen($json) > self::MAX_BYTES) {
			return null;
		}
		$list = json_decode($json, false, 32);
		if (!$list instanceof stdClass || !is_array($list->extensions ?? null)) {
			return null;
		}
		$entries = [];
		foreach ($list->extensions as $entry) {
			if (!$entry instanceof stdClass) {
				return null;
			}
			$entry = get_object_vars($entry);
			$entry['compatibility'] ??= '✔';
			if (is_int($entry['version'] ?? null) || is_float($entry['version'] ?? null)) {
				$entry['version'] = (string)$entry['version'];
			}
			foreach (['name', 'entrypoint', 'author', 'description', 'version', 'type', 'url', 'method', 'directory', 'compatibility'] as $key) {
				if (!is_string($entry[$key] ?? null) || trim($entry[$key]) === '') {
					return null;
				}
			}
			if (!in_array($entry['type'], ['system', 'user'], true) ||
				preg_match('/^[a-z0-9_]+$/iD', $entry['entrypoint']) !== 1 ||
				preg_match('~^https://[^\s]+$~D', $entry['url']) !== 1 ||
				($entry['compatibility'] !== '✔' && preg_match('/^\d+(?:\.\d+)+$/D', $entry['compatibility']) !== 1)) {
				return null;
			}
			/** @var CatalogEntry $entry */
			$entries[] = $entry;
		}
		return $entries;
	}

	public static function repository(string $url): string {
		if (preg_match('~^https://github\.com/([a-z0-9_.-]+/[a-z0-9_.-]+?)(?:\.git)?/?$~iD', $url, $matches) !== 1) {
			return '';
		}
		return 'github.com/' . strtolower($matches[1]);
	}

	/** @param CatalogEntry $entry */
	public static function id(array $entry): string {
		return hash('sha256', (self::repository($entry['url']) ?: $entry['url']) . "\n" . $entry['directory'] . "\n" . $entry['entrypoint']);
	}

	/** @param CatalogEntry $entry */
	public static function compatible(array $entry): bool {
		return $entry['compatibility'] === '✔' || version_compare(FRESHRSS_VERSION, $entry['compatibility'], '>=');
	}

	/**
	 * Fetch only from the fixed HTTPS endpoints used for the marketplace.
	 * Redirects are refused.
	 */
	public static function download(string $url, int $limit): string {
		if (preg_match('~^https://(?:raw\.githubusercontent\.com|codeload\.github\.com)/~D', $url) !== 1) {
			throw new RuntimeException('Invalid marketplace download endpoint');
		}
		$proxyOptions = array_intersect_key(FreshRSS_Context::systemConf()->curl_options, [
			CURLOPT_PROXY => true,
			CURLOPT_PROXYTYPE => true,
		]);
		$resolve = empty($proxyOptions[CURLOPT_PROXY]) ? FreshRSS_http_Util::getCurlResolveInfo($url) : [];
		if (!is_array($resolve)) {
			throw new RuntimeException('Cannot resolve marketplace download endpoint');
		}

		$body = '';
		$curl = curl_init($url);
		if ($curl === false) {
			throw new RuntimeException('Cannot initialise marketplace download');
		}
		curl_setopt_array($curl, [
			CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_RESOLVE => $resolve,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 25,
			CURLOPT_MAXFILESIZE => $limit,
			CURLOPT_WRITEFUNCTION => static function (CurlHandle $handle, string $chunk) use (&$body, $limit): int {
				if (strlen($body) + strlen($chunk) > $limit) {
					return 0;
				}
				$body .= $chunk;
				return strlen($chunk);
			},
		] + $proxyOptions);
		$success = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
		if ($success !== true || $status !== 200 || $body === '') {
			throw new RuntimeException('Marketplace download failed (HTTP ' . $status . ', cURL ' . curl_errno($curl) . ')');
		}
		return $body;
	}
}
