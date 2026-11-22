<?php
declare(strict_types=1);

/**
 * The controller to manage extensions.
 */
class FreshRSS_extension_Controller extends FreshRSS_ActionController {
	/**
	 * This action is called before every other action in that class. It is
	 * the common boiler plate for every action. It is triggered by the
	 * underlying framework.
	 */
	#[\Override]
	public function firstAction(): void {
		if (!FreshRSS_Auth::hasAccess()) {
			Minz_Error::error(403);
		}
	}

	/**
	 * This action lists all the extensions available to the current user.
	 */
	public function indexAction(): void {
		FreshRSS_View::prependTitle(_t('admin.extensions.title') . ' · ');
		$this->view->extension_list = [
			'system' => [],
			'user' => [],
		];

		[$catalog] = FreshRSS_ExtensionCatalog::get();
		$this->view->catalog_enabled = FreshRSS_Context::userConf()->retrieve_extension_list;
		$this->view->marketplace = $this->view->catalog_enabled ? ['official' => [], 'community' => []] : [];
		$extensions = Minz_ExtensionManager::listExtensions();
		$listed = [];
		$inventory = $catalog === [] ? [] : FreshRSS_ExtensionMarketplace::inventory();
		foreach ($catalog as $entry) {
			$status = FreshRSS_ExtensionMarketplace::status($entry, $catalog, $inventory);
			$local = $extensions[$entry['name']] ?? null;
			if ($local !== null && ($local->getEntrypoint() !== $entry['entrypoint'] ||
				isset($listed[$local->getName()]) || $status['reason'] === 'ambiguous')) {
				$local = null;
			}
			if ($local !== null) {
				$listed[$local->getName()] = true;
				$status['version'] = $local->getVersion();
			}
			$group = FreshRSS_ExtensionCatalog::repository($entry['url']) === FreshRSS_ExtensionCatalog::OFFICIAL_REPOSITORY ?
				'official' : 'community';
			$this->view->marketplace[$group][] = ['entry' => $entry, 'status' => $status, 'local' => $local];
		}
		foreach ($extensions as $extension) {
			if (!isset($listed[$extension->getName()])) {
				$this->view->extension_list[$extension->getType()][] = $extension;
			}
		}
	}

	public function refreshAction(): void {
		$redirect = ['c' => 'extension', 'a' => 'index'];
		if (!Minz_Request::isPost()) {
			Minz_Error::error(405);
			return;
		}
		if (!FreshRSS_Context::userConf()->retrieve_extension_list) {
			Minz_Request::bad(_t('admin.extensions.marketplace.privacy'), $redirect);
			return;
		}
		[, $success] = FreshRSS_ExtensionCatalog::get(true);
		if ($success) {
			Minz_Request::good(_t('admin.extensions.marketplace.refreshed'), $redirect);
		} else {
			Minz_Request::bad(_t('admin.extensions.marketplace.refresh_failed'), $redirect);
		}
	}

	public function marketplaceAction(): void {
		if (!$this->authorizeFileOperation()) {
			return;
		}
		$redirect = ['c' => 'extension', 'a' => 'index'];
		$id = Minz_Request::paramString('id');
		$action = Minz_Request::paramString('operation');
		[$catalog] = FreshRSS_ExtensionCatalog::get();
		foreach ($catalog as $entry) {
			if (FreshRSS_ExtensionCatalog::id($entry) !== $id) {
				continue;
			}
			try {
				FreshRSS_ExtensionMarketplace::apply($entry, $catalog, $action);
				Minz_Request::good(_t('admin.extensions.marketplace.saved'), $redirect);
			} catch (RuntimeException $e) {
				$this->marketplaceError($e, $redirect);
			}
			return;
		}
		Minz_Request::bad(_t('admin.extensions.marketplace.changed'), $redirect);
	}

	private function authorizeFileOperation(): bool {
		if (!Minz_Request::isPost()) {
			Minz_Error::error(405);
			return false;
		}
		if (!FreshRSS_Auth::hasAccess('admin') || !FreshRSS_Auth::isCsrfOk()) {
			Minz_Error::error(403);
			return false;
		}
		return !FreshRSS_Auth::requestReauth(['c' => 'extension', 'a' => 'index']);
	}

	/** @param array{c:string,a:string} $redirect */
	private function marketplaceError(RuntimeException $e, array $redirect): void {
		$reason = $e->getMessage();
		if (!in_array($reason, ['ambiguous', 'origin', 'incompatible', 'unsupported', 'read_only', 'occupied', 'changed'], true)) {
			Minz_Log::warning($reason);
			$reason = 'failed';
		}
		Minz_Request::bad(_t('admin.extensions.marketplace.' . $reason), $redirect);
	}

	/**
	 * This action handles configuration of a given extension.
	 *
	 * Only administrator can configure a system extension.
	 *
	 * Parameters are:
	 * - e: the extension name (urlencoded)
	 * - additional parameters which should be handle by the extension
	 *   handleConfigureAction() method (POST request).
	 */
	public function configureAction(): void {
		if (Minz_Request::paramBoolean('ajax')) {
			$this->view->_layout(null);
		} elseif (Minz_Request::paramBoolean('slider')) {
			$this->indexAction();
			$this->view->_path('extension/index.phtml');
		}

		$ext_name = urldecode(Minz_Request::paramString('e'));
		$ext = Minz_ExtensionManager::findExtension($ext_name);

		if ($ext === null) {
			Minz_Error::error(404);
			return;
		}
		if ($ext->getType() === 'system' && !FreshRSS_Auth::hasAccess('admin')) {
			Minz_Error::error(403);
			return;
		}

		FreshRSS_View::prependTitle($ext->getName() . ' · ' . _t('admin.extensions.title') . ' · ');
		$this->view->extension = $ext;
		try {
			$this->view->extension->handleConfigureAction();
		} catch (Minz_Exception $e) {	// @phpstan-ignore catch.neverThrown (Thrown by extensions)
			Minz_Log::error('Error while configuring extension ' . $ext->getName() . ': ' . $e->getMessage());
			Minz_Request::bad(_t('feedback.extensions.enable.ko', $ext_name, _url('index', 'logs')), ['c' => 'extension', 'a' => 'index']);
		}
	}

	/**
	 * This action enables a disabled extension for the current user.
	 *
	 * System extensions can only be enabled by an administrator.
	 * This action must be reached by a POST request.
	 *
	 * Parameter is:
	 * - e: the extension name (urlencoded).
	 */
	public function enableAction(): void {
		$url_redirect = ['c' => 'extension', 'a' => 'index'];

		if (Minz_Request::isPost()) {
			$ext_name = urldecode(Minz_Request::paramString('e'));
			$ext = Minz_ExtensionManager::findExtension($ext_name);

			if (is_null($ext)) {
				Minz_Request::bad(_t('feedback.extensions.not_found', $ext_name), $url_redirect);
				return;
			}

			if ($ext->isEnabled()) {
				Minz_Request::bad(_t('feedback.extensions.already_enabled', $ext_name), $url_redirect);
			}

			$type = $ext->getType();
			if ($type !== 'user' && !FreshRSS_Auth::hasAccess('admin')) {
				Minz_Request::bad(_t('feedback.extensions.no_access', $ext_name), $url_redirect);
				return;
			}

			$conf = null;
			if ($type === 'system') {
				$conf = FreshRSS_Context::systemConf();
			} elseif ($type === 'user') {
				$conf = FreshRSS_Context::userConf();
			}

			$res = $ext->install();

			if ($conf !== null && $res === true) {
				$ext_list = $conf->extensions_enabled;
				$ext_list = array_filter($ext_list, static function (string $key) use ($type) {
					// Remove from list the extensions that have disappeared or changed type
					$extension = Minz_ExtensionManager::findByActivationKey($key);
					return $extension !== null && $extension->getType() === $type;
				}, ARRAY_FILTER_USE_KEY);

				$ext_list[Minz_ExtensionManager::activationKey($ext)] = true;
				$conf->extensions_enabled = $ext_list;
				$conf->save();

				Minz_Request::good(
					_t('feedback.extensions.enable.ok', $ext_name),
					$url_redirect,
					showNotification: FreshRSS_Context::userConf()->good_notification_timeout > 0
				);
			} else {
				Minz_Log::warning('Cannot enable extension ' . $ext_name . ': ' . $res);
				Minz_Request::bad(_t('feedback.extensions.enable.ko', $ext_name, _url('index', 'logs')), $url_redirect);
			}
		}

		Minz_Request::forward($url_redirect, true);
	}

	/**
	 * This action disables an enabled extension for the current user.
	 *
	 * System extensions can only be disabled by an administrator.
	 * This action must be reached by a POST request.
	 *
	 * Parameter is:
	 * - e: the extension name (urlencoded).
	 */
	public function disableAction(): void {
		$url_redirect = ['c' => 'extension', 'a' => 'index'];

		if (Minz_Request::isPost()) {
			$ext_name = urldecode(Minz_Request::paramString('e'));
			$ext = Minz_ExtensionManager::findExtension($ext_name);

			if (is_null($ext)) {
				Minz_Request::bad(_t('feedback.extensions.not_found', $ext_name), $url_redirect);
				return;
			}

			if (!$ext->isEnabled()) {
				Minz_Request::bad(_t('feedback.extensions.not_enabled', $ext_name), $url_redirect);
			}

			$type = $ext->getType();
			if ($type !== 'user' && !FreshRSS_Auth::hasAccess('admin')) {
				Minz_Request::bad(_t('feedback.extensions.no_access', $ext_name), $url_redirect);
				return;
			}

			$conf = null;
			if ($type === 'system') {
				$conf = FreshRSS_Context::systemConf();
			} elseif ($type === 'user') {
				$conf = FreshRSS_Context::userConf();
			}

			$res = $ext->uninstall();

			if ($conf !== null && $res === true) {
				$ext_list = $conf->extensions_enabled;
				$ext_list = array_filter($ext_list, static function (string $key) use ($type) {
					// Remove from list the extensions that have disappeared or changed type
					$extension = Minz_ExtensionManager::findByActivationKey($key);
					return $extension !== null && $extension->getType() === $type;
				}, ARRAY_FILTER_USE_KEY);

				$ext_list[Minz_ExtensionManager::activationKey($ext)] = false;
				$conf->extensions_enabled = $ext_list;
				$conf->save();

				Minz_Request::good(
					_t('feedback.extensions.disable.ok', $ext_name),
					$url_redirect,
					showNotification: FreshRSS_Context::userConf()->good_notification_timeout > 0
				);
			} else {
				Minz_Log::warning('Cannot disable extension ' . $ext_name . ': ' . $res);
				Minz_Request::bad(_t('feedback.extensions.disable.ko', $ext_name, _url('index', 'logs')), $url_redirect);
			}
		}

		Minz_Request::forward($url_redirect, true);
	}

	public function removeAction(): void {
		if (!$this->authorizeFileOperation()) {
			return;
		}
		$redirect = ['c' => 'extension', 'a' => 'index'];
		$name = urldecode(Minz_Request::paramString('e'));
		$extension = Minz_ExtensionManager::findExtension($name);
		if ($extension === null) {
			Minz_Request::bad(_t('feedback.extensions.not_found', $name), $redirect);
			return;
		}
		if (!FreshRSS_ExtensionMarketplace::canRemove($extension)) {
			Minz_Request::bad(_t('admin.extensions.marketplace.cannot_remove'), $redirect);
			return;
		}
		try {
			FreshRSS_ExtensionMarketplace::remove($extension);
			Minz_Request::good(_t('feedback.extensions.removed', $name), $redirect);
		} catch (RuntimeException $e) {
			$this->marketplaceError($e, $redirect);
		}
	}

	// Supported types with their associated content type
	public const MIME_TYPES = [
		'css' => 'text/css; charset=UTF-8',
		'gif' => 'image/gif',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'js' => 'application/javascript; charset=UTF-8',
		'png' => 'image/png',
		'svg' => 'image/svg+xml',
	];

	public function serveAction(): void {
		$extensionName = Minz_Request::paramString('x');
		$filename = Minz_Request::paramString('f');
		$mimeType = pathinfo($filename, PATHINFO_EXTENSION);
		if ($extensionName === '' || $filename === '' || $mimeType === '' || empty(self::MIME_TYPES[$mimeType])) {
			header('HTTP/1.1 400 Bad Request');
			die('Bad Request!');
		}
		$extension = Minz_ExtensionManager::findExtension($extensionName);
		if ($extension === null || !$extension->isEnabled() || ($mtime = $extension->mtimeFile($filename)) === null) {
			header('HTTP/1.1 404 Not Found');
			die('Not Found!');
		}

		$this->view->_layout(null);

		$content_type = self::MIME_TYPES[$mimeType];
		header("Content-Type: {$content_type}");
		header("Content-Disposition: inline; filename='{$filename}'");
		header('X-Content-Type-Options: nosniff');
		header('Referrer-Policy: same-origin');
		if (file_exists(DATA_PATH . '/no-cache.txt') || !httpConditional($mtime, cacheSeconds: 604800, cachePrivacy: 0)) {
			$contents = $extension->getFile($filename);
			$this->view->content = is_string($contents) ? $contents : '';
		}
	}
}
