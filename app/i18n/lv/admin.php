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
		'allow_anonymous' => 'Ļaut anonīmi lasīt noklusējuma lietotāja rakstus (%s)',
		'allow_anonymous_refresh' => 'Atļaut anonīmu rakstu atsvaidzināšanu',
		'api_enabled' => 'Atļaut <abbr>API</abbr> piekļuvi <small>(nepieciešams mobilajām lietotnēm and sharing user queries)</small>',	// DIRTY - Allow <abbr>API</abbr> access <small>(required for mobile apps and sharing user queries)</small>
		'form' => 'Tīmekļa veidlapa (tradicionālā)',
		'http' => 'HTTP (advanced: managed by Web server, OIDC, SSO…)',	// TODO
		'none' => 'Nav (bīstami)',
		'title' => 'Autentifikācija',
		'token' => 'Galvenā autentifikācijas žetons',
		'token_help' => 'Ļauj piekļūt visām lietotāja RSS izvadēm, kā arī atsvaidzināt barotnes bez autentifikācijas:',
		'type' => 'Autentifikācijas metode',
	),
	'extensions' => array(
		'author' => 'Autors',
		'community' => 'Pieejamie sabiedrības paplašinājumi',
		'description' => 'Apraksts',
		'disabled' => 'Atspējots',
		'empty_list' => 'Nav instalētu paplašinājumu',
		'empty_list_help' => 'Check the logs to determine the reason behind the empty extension list.',	// TODO
		'enabled' => 'Ieslēgts',
		'is_compatible' => 'Is compatible',	// TODO
		'latest' => 'Instalēts',
		'marketplace' => array(
			'ambiguous' => 'Multiple extensions match this entry.',	// TODO
			'cannot_remove' => 'This extension directory is read-only or managed outside FreshRSS.',	// TODO
			'changed' => 'The extension or catalogue has changed. Reload the page and try again.',	// TODO
			'community' => 'Unofficial',	// TODO
			'confirm_remove' => 'Uninstall this extension for everyone on this instance?',	// TODO
			'confirm_source' => 'Install code from %s on this instance?',	// TODO
			'failed' => 'The extension operation failed. See the FreshRSS logs for details.',	// TODO
			'incompatible' => 'This version is incompatible with this FreshRSS installation.',	// TODO
			'install' => 'Install',	// TODO
			'new_version' => 'Version %s is available.',	// TODO
			'occupied' => 'The destination already exists.',	// TODO
			'official' => 'Official',	// TODO
			'origin' => 'The installed copy has no verified marketplace origin or has been modified.',	// TODO
			'privacy' => 'Catalogue retrieval is disabled in your privacy preferences.',	// TODO
			'read_only' => 'The extensions directory is read-only.',	// TODO
			'refresh' => 'Refresh catalogue',	// TODO
			'refresh_failed' => 'The catalogue could not be refreshed. The last valid copy remains available if cached.',	// TODO
			'refreshed' => 'Extension catalogue refreshed.',	// TODO
			'saved' => 'Extension files saved.',	// TODO
			'uninstall' => 'Uninstall',	// TODO
			'unsupported' => 'There is no approved package for this version.',	// TODO
			'upgrade' => 'Update',	// TODO
		),
		'name' => 'Vārds',
		'no_configure_view' => 'Šo paplašinājumu nevar konfigurēt.',
		'system' => array(
			'_' => 'Sistēmas paplašinājumi',
			'no_rights' => 'Sistēmas paplašinājums (jums nav vajadzīgo atļauju)',
		),
		'title' => 'Paplašinājumi',
		'update' => 'Pieejams atjauninājums',
		'user' => 'Lietotāja paplašinājumi',
		'version' => 'Versija',
	),
	'stats' => array(
		'_' => 'Statistika',
		'all_feeds' => 'Visas barotnes',
		'category' => 'Kategorija',
		'date_published' => 'Publication date',	// TODO
		'date_received' => 'Received date',	// TODO
		'entry_count' => 'Ierakstu skaits',
		'entry_per_category' => 'Ieraksti katrā kategorijā',
		'entry_per_day' => 'Ieraksti dienā (pēdējās 30 dienas)',
		'entry_per_day_of_week' => 'Katrā nedēļas dienā (vidēji: %.2f ziņojumu)',
		'entry_per_hour' => 'Katrā stundā (vidēji: %.2f ziņojumu)',
		'entry_per_month' => 'Katrā mēnesī (vidēji: %.2f ziņojumu)',
		'entry_repartition' => 'Ierakstu sadalījums',
		'feed' => 'Barotne',
		'feed_per_category' => 'Barotnes pa kategorijām',
		'idle' => 'Neaktīvās barotnes',
		'main' => 'Galvenās statistikas',
		'main_stream' => 'Galvenā plūsma',
		'nb_unreads' => 'Number of unread articles',	// TODO
		'no_idle' => 'Nav neaktīvu barotņu!',
		'number_entries' => '%d raksti',
		'overview' => 'Overview',	// TODO
		'percent_of_total' => '% no kopsummas',
		'repartition' => 'Rakstu pārdalīšana: %s',	// DIRTY - Articles repartition: %s
		'status_favorites' => 'Mīļākie',
		'status_read' => 'Izlasīti',
		'status_total' => 'Kopā',
		'status_unread' => 'Neizlasīti',
		'title' => 'Statistika',
		'top_feed' => 'Top 10 barotnes',
		'unread_dates' => 'Dates with most unread articles',	// TODO
	),
	'system' => array(
		'_' => 'Sistēmas konfigurācija',
		'auto-update-url' => 'Automātiskās atjaunināšanas servera URL',
		'base-url' => array(
			'_' => 'Bāzes URL',
			'recommendation' => 'Automātiska ieteikšana: <kbd>%s</kbd>',
		),
		'closed_registration_message' => 'Message if registrations are closed',	// TODO
		'cookie-duration' => array(
			'help' => 'sekundēs',
			'number' => 'Pieteikšanās ilgums',
		),
		'default_closed_registration_message' => 'This server does not accept new registrations at the moment.',	// TODO
		'force_email_validation' => 'Piespiedu e-pasta adreses validēšana',
		'instance-name' => 'Instances nosaukums',
		'internal-host-allowlist' => array(
			'_' => 'Internal host allowlist',	// TODO
			'help' => 'One entry per line:<ul><li>A <code>host:port</code>. For instance <code>127.0.0.1:8080</code> or <code>rss-bridge:80</code></li><li>A CIDR notation. For instance <code>0.0.0.0/0</code> to allow any IPv4, <code>::/0</code> to allow any IPv6</li><li>A <code>*</code> to allow any host (unsafe)</li></ul>',	// TODO
		),
		'max-categories' => 'Maksimālais kategoriju skaits vienam lietotājam',
		'max-feeds' => 'Maksimālais barotņu skaits vienam lietotājam',
		'override-by-env-var' => 'This setting is set by the environment variable <kbd>%s</kbd>.',	// TODO
		'registration' => array(
			'number' => 'Maksimālais kontu skaits',
			'select' => array(
				'label' => 'Reģistrācijas veidlapa',
				'option' => array(
					'noform' => 'Atspējots: Nav reģistrācijas veidlapas',
					'nolimit' => 'Ieslēgts: Kontu skaits nav ierobežots',
					'setaccountsnumber' => 'Maksimālā kontu skaita iestatīšana',
				),
			),
			'status' => array(
				'disabled' => 'Veidlapa atspējota',
				'enabled' => 'Veidlapa ieslēgta',
			),
			'title' => 'Lietotāja reģistrācijas veidlapa',
		),
		'sensitive-parameter' => 'Jutīgs parametrs. Rediģējiet manuāli <kbd>./data/config.php</kbd>',
		'tos' => array(
			'disabled' => 'nav dots',
			'enabled' => '<a href="./?a=tos">ir ieslēgts</a>',
			'help' => 'Kā iespējot <a href="https://freshrss.github.io/FreshRSS/en/admins/12_User_management.html#enable-terms-of-service-tos" target="_blank">pakalpojumu sniegšanas noteikumus</a>',
		),
		'websub' => array(
			'help' => 'Par <a href="https://freshrss.github.io/FreshRSS/en/users/WebSub.html" target="_blank">WebSub</a>',
		),
	),
	'update' => array(
		'_' => 'Atjaunināt sistēmu',
		'apply' => 'Pieteikties',
		'changelog' => 'Izmaiņu žurnāls',
		'check' => 'Jaunu atjauninājumu pārbaude',
		'copiedFromURL' => 'update.php kopēts no %s uz ./data',
		'current_version' => 'Jūsu pašreizējā versija',
		'last' => 'Pēdējā verifikācija',
		'loading' => 'Atjaunināšana…',
		'none' => 'Nav jāpiemēro atjauninājums',
		'releaseChannel' => array(
			'_' => 'Izlaiduma kanāls',
			'edge' => 'Ritošā atbrīvošana (“edge”)',
			'latest' => 'Stabila atbrīvošana (“latest”)',	// DIRTY - Versioned release (“latest”)
		),
		'title' => 'Atjaunināt sistēmu',
		'viaGit' => 'Atjaunināt, izmantojot git un GitHub.com sākts',
	),
	'user' => array(
		'admin' => 'Administrators',
		'article_count' => 'Raksti',
		'back_to_manage' => '← Atgriezties lietotāju sarakstā',
		'create' => 'Uztaisīt jaunu lietotāju',
		'database_size' => 'Datubāzes izmērs',
		'email' => 'E-pasta adreses',
		'enabled' => 'Ieslēgts',
		'feed_count' => 'Barotnes',
		'is_admin' => 'Ir administrators',
		'language' => 'Valoda',
		'last_user_activity' => 'Pēdējā lietotāja darbība',
		'list' => 'Lietotāju saraksts',
		'number' => 'Ir izveidots %d konts',
		'numbers' => 'Ir izveidoti %d kontu',
		'password_form' => 'Parole<br /><small>(Web-formas pieteikšanās metodei)</small>',
		'password_format' => 'Vismaz 7 rakstzīmes',
		'title' => 'Pārvaldīt lietotājus',
		'username' => 'Lietotājvārds',
	),
);
