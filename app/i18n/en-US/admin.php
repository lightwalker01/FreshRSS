<?php

/******************************************************************************
 * Each entry of that file can be associated with a comment to indicate its   *
 * state. When there is no comment, it means the entry is fully translated.   *
 * The recognized comments are (comment matching is case-insensitive):        *
 *   + TODO: the entry has never been translated.                             *
 *   + DIRTY: the entry has been translated but needs to be updated.          *
 *   + IGNORE: the entry does not need to be translated.                      *
 * When a comment is not recognized, it is discarded.                         *
 ******************************************************************************/

return array(
	'auth' => array(
		'allow_anonymous' => 'Allow anonymous reading of the default user’s articles (%s)',	// IGNORE
		'allow_anonymous_refresh' => 'Allow anonymous refresh of the articles',	// IGNORE
		'api_enabled' => 'Allow <abbr>API</abbr> access <small>(required for mobile apps and sharing user queries)</small>',	// IGNORE
		'form' => 'Web form (traditional)',	// IGNORE
		'http' => 'HTTP (advanced: managed by Web server, OIDC, SSO…)',	// IGNORE
		'none' => 'None (dangerous)',	// IGNORE
		'title' => 'Authentication',	// IGNORE
		'token' => 'Master authentication token',	// IGNORE
		'token_help' => 'Allows access to all RSS outputs of the user as well as refreshing feeds without authentication:',	// IGNORE
		'type' => 'Authentication method',	// IGNORE
	),
	'extensions' => array(
		'author' => 'Author',	// IGNORE
		'community' => 'Available community extensions',	// IGNORE
		'description' => 'Description',	// IGNORE
		'disabled' => 'Disabled',	// IGNORE
		'empty_list' => 'There are no installed extensions',	// IGNORE
		'empty_list_help' => 'Check the logs to determine the reason behind the empty extension list.',	// IGNORE
		'enabled' => 'Enabled',	// IGNORE
		'is_compatible' => 'Is compatible',	// IGNORE
		'latest' => 'Installed',	// IGNORE
		'marketplace' => array(
			'ambiguous' => 'Multiple extensions match this entry.',	// IGNORE
			'cannot_remove' => 'This extension directory is read-only or managed outside FreshRSS.',	// IGNORE
			'changed' => 'The extension or catalogue has changed. Reload the page and try again.',	// IGNORE
			'community' => 'Unofficial',	// IGNORE
			'confirm_remove' => 'Uninstall this extension for everyone on this instance?',	// IGNORE
			'confirm_source' => 'Install code from %s on this instance?',	// IGNORE
			'failed' => 'The extension operation failed. See the FreshRSS logs for details.',	// IGNORE
			'incompatible' => 'This version is incompatible with this FreshRSS installation.',	// IGNORE
			'install' => 'Install',	// IGNORE
			'new_version' => 'Version %s is available.',	// IGNORE
			'occupied' => 'The destination already exists.',	// IGNORE
			'official' => 'Official',	// IGNORE
			'origin' => 'The installed copy has no verified marketplace origin or has been modified.',	// IGNORE
			'privacy' => 'Catalogue retrieval is disabled in your privacy preferences.',	// IGNORE
			'read_only' => 'The extensions directory is read-only.',	// IGNORE
			'refresh' => 'Refresh catalogue',	// IGNORE
			'refresh_failed' => 'The catalogue could not be refreshed. The last valid copy remains available if cached.',	// IGNORE
			'refreshed' => 'Extension catalogue refreshed.',	// IGNORE
			'saved' => 'Extension files saved.',	// IGNORE
			'uninstall' => 'Uninstall',	// IGNORE
			'unsupported' => 'There is no approved package for this version.',	// IGNORE
			'upgrade' => 'Update',	// IGNORE
		),
		'name' => 'Name',	// IGNORE
		'no_configure_view' => 'This extension cannot be configured.',	// IGNORE
		'system' => array(
			'_' => 'System extensions',	// IGNORE
			'no_rights' => 'System extension (you do not have the required permissions)',	// IGNORE
		),
		'title' => 'Extensions',	// IGNORE
		'update' => 'Update available',	// IGNORE
		'user' => 'User extensions',	// IGNORE
		'version' => 'Version',	// IGNORE
	),
	'stats' => array(
		'_' => 'Statistics',	// IGNORE
		'all_feeds' => 'All feeds',	// IGNORE
		'category' => 'Category',	// IGNORE
		'date_published' => 'Publication date',	// IGNORE
		'date_received' => 'Received date',	// IGNORE
		'entry_count' => 'Entry count',	// IGNORE
		'entry_per_category' => 'Entries per category',	// IGNORE
		'entry_per_day' => 'Entries per day (last 30 days)',	// IGNORE
		'entry_per_day_of_week' => 'Per day of week (average: %.2f messages)',	// IGNORE
		'entry_per_hour' => 'Per hour (average: %.2f messages)',	// IGNORE
		'entry_per_month' => 'Per month (average: %.2f messages)',	// IGNORE
		'entry_repartition' => 'Entries repartition',	// IGNORE
		'feed' => 'Feed',	// IGNORE
		'feed_per_category' => 'Feeds per category',	// IGNORE
		'idle' => 'Idle feeds',	// IGNORE
		'main' => 'Main statistics',	// IGNORE
		'main_stream' => 'Main stream',	// IGNORE
		'nb_unreads' => 'Number of unread articles',	// IGNORE
		'no_idle' => 'There are no idle feeds!',	// IGNORE
		'number_entries' => '%d articles',	// IGNORE
		'overview' => 'Overview',	// IGNORE
		'percent_of_total' => '% of total',	// IGNORE
		'repartition' => 'Articles repartition: %s',	// IGNORE
		'status_favorites' => 'Favorites',
		'status_read' => 'Read',	// IGNORE
		'status_total' => 'Total',	// IGNORE
		'status_unread' => 'Unread',	// IGNORE
		'title' => 'Statistics',	// IGNORE
		'top_feed' => 'Top ten feeds',	// IGNORE
		'unread_dates' => 'Dates with most unread articles',	// IGNORE
	),
	'system' => array(
		'_' => 'System configuration',	// IGNORE
		'auto-update-url' => 'Auto-update server URL',	// IGNORE
		'base-url' => array(
			'_' => 'Base URL',	// IGNORE
			'recommendation' => 'Automatic recommendation: <kbd>%s</kbd>',	// IGNORE
		),
		'closed_registration_message' => 'Message if registrations are closed',	// IGNORE
		'cookie-duration' => array(
			'help' => 'in seconds',	// IGNORE
			'number' => 'Duration to keep logged in',	// IGNORE
		),
		'default_closed_registration_message' => 'This server does not accept new registrations at the moment.',	// IGNORE
		'force_email_validation' => 'Force email address validation',	// IGNORE
		'instance-name' => 'Instance name',	// IGNORE
		'internal-host-allowlist' => array(
			'_' => 'Internal host allowlist',	// IGNORE
			'help' => 'One entry per line:<ul><li>A <code>host:port</code>. For instance <code>127.0.0.1:8080</code> or <code>rss-bridge:80</code></li><li>A CIDR notation. For instance <code>0.0.0.0/0</code> to allow any IPv4, <code>::/0</code> to allow any IPv6</li><li>A <code>*</code> to allow any host (unsafe)</li></ul>',	// IGNORE
		),
		'max-categories' => 'Max number of categories per user',	// IGNORE
		'max-feeds' => 'Max number of feeds per user',	// IGNORE
		'override-by-env-var' => 'This setting is set by the environment variable <kbd>%s</kbd>.',	// IGNORE
		'registration' => array(
			'number' => 'Max number of accounts',	// IGNORE
			'select' => array(
				'label' => 'Registration form',	// IGNORE
				'option' => array(
					'noform' => 'Disabled: No registration form',	// IGNORE
					'nolimit' => 'Enabled: No limit of accounts',	// IGNORE
					'setaccountsnumber' => 'Set max. number of accounts',	// IGNORE
				),
			),
			'status' => array(
				'disabled' => 'Form disabled',	// IGNORE
				'enabled' => 'Form enabled',	// IGNORE
			),
			'title' => 'User registration form',	// IGNORE
		),
		'sensitive-parameter' => 'Sensitive parameter. Edit manually in <kbd>./data/config.php</kbd>',	// IGNORE
		'tos' => array(
			'disabled' => 'is not given',	// IGNORE
			'enabled' => '<a href="./?a=tos">is enabled</a>',	// IGNORE
			'help' => 'How to <a href="https://freshrss.github.io/FreshRSS/en/admins/12_User_management.html#enable-terms-of-service-tos" target="_blank">enable the Terms of Service</a>',	// IGNORE
		),
		'websub' => array(
			'help' => 'About <a href="https://freshrss.github.io/FreshRSS/en/users/WebSub.html" target="_blank">WebSub</a>',	// IGNORE
		),
	),
	'update' => array(
		'_' => 'Update system',	// IGNORE
		'apply' => 'Apply',	// IGNORE
		'changelog' => 'Changelog',	// IGNORE
		'check' => 'Check for new updates',	// IGNORE
		'copiedFromURL' => 'update.php copied from %s to ./data',	// IGNORE
		'current_version' => 'Current installed version',	// IGNORE
		'last' => 'Last verification',	// IGNORE
		'loading' => 'Updating…',	// IGNORE
		'none' => 'No update to apply',	// IGNORE
		'releaseChannel' => array(
			'_' => 'Release channel',	// IGNORE
			'edge' => 'Rolling release (“edge”)',	// IGNORE
			'latest' => 'Versioned release (“latest”)',	// IGNORE
		),
		'title' => 'Update system',	// IGNORE
		'viaGit' => 'Update via git and GitHub.com started',	// IGNORE
	),
	'user' => array(
		'admin' => 'Administrator',	// IGNORE
		'article_count' => 'Articles',	// IGNORE
		'back_to_manage' => '← Return to user list',	// IGNORE
		'create' => 'Create new user',	// IGNORE
		'database_size' => 'Database size',	// IGNORE
		'email' => 'Email address',	// IGNORE
		'enabled' => 'Enabled',	// IGNORE
		'feed_count' => 'Feeds',	// IGNORE
		'is_admin' => 'Is admin',	// IGNORE
		'language' => 'Language',	// IGNORE
		'last_user_activity' => 'Last user activity',	// IGNORE
		'list' => 'User list',	// IGNORE
		'number' => 'There is %d account created',	// IGNORE
		'numbers' => 'There are %d accounts created',	// IGNORE
		'password_form' => 'Password<br /><small>(for the Web-form login method)</small>',	// IGNORE
		'password_format' => 'At least 7 characters',	// IGNORE
		'title' => 'Manage users',	// IGNORE
		'username' => 'Username',	// IGNORE
	),
);
