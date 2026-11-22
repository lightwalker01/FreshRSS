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
		'allow_anonymous' => 'Izinkan pembacaan artikel pengguna default (%s) secara awanama',
		'allow_anonymous_refresh' => 'Izinkan untuk memuat ulang artikel awanama',
		'api_enabled' => 'Izinkan akses <abbr>API</abbr> <small>(Diperlukan untuk aplikasi seluler dan untuk membagikan pencarian pengguna)</small>',
		'form' => 'Formulir Web (klasik)',
		'http' => 'HTTP (tingkat lanjut: dikelola oleh server Web, OIDC, SSO, dll.)',
		'none' => 'Tidak ada (berbahaya)',
		'title' => 'Autentikasi',
		'token' => 'Token autentikasi utama',
		'token_help' => 'Mengizinkan akses ke semua RSS pengguna serta menyegarkan umpan tanpa autentikasi:',
		'type' => 'Metode autentikasi',
	),
	'extensions' => array(
		'author' => 'Pengembang',
		'community' => 'Ekstensi komunitas yang tersedia',
		'description' => 'Keterangan',
		'disabled' => 'Dinonaktifkan',
		'empty_list' => 'Tidak ada ekstensi yang terpasang',
		'empty_list_help' => 'Periksa log untuk menemukan alasan daftar ekstensi yang kosong.',
		'enabled' => 'Diaktifkan',
		'is_compatible' => 'Kompatibel',
		'latest' => 'Terpasang',
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
		'name' => 'Nama',
		'no_configure_view' => 'Ekstensi ini tidak dapat dikonfigurasi.',
		'system' => array(
			'_' => 'Ekstensi sistem',
			'no_rights' => 'Ekstensi sistem (Anda tidak memiliki izin yang diperlukan)',
		),
		'title' => 'Ekstensi',
		'update' => 'Pembaruan tersedia',
		'user' => 'Ekstensi pengguna',
		'version' => 'Versi',
	),
	'stats' => array(
		'_' => 'Statistik',
		'all_feeds' => 'Semua umpan',
		'category' => 'Kategori',
		'date_published' => 'Tanggal terbit',
		'date_received' => 'Tanggal diterima',
		'entry_count' => 'Jumlah entri',
		'entry_per_category' => 'Entri per kategori',
		'entry_per_day' => 'Entri per hari (30 hari terakhir)',
		'entry_per_day_of_week' => 'Per hari dalam seminggu (rata-rata: %.2f pesan)',
		'entry_per_hour' => 'Per jam (rata-rata %.2f pesan)',
		'entry_per_month' => 'Per bulan (rata -rata: %.2f pesan)',
		'entry_repartition' => 'Pengkategorian Entri',
		'feed' => 'Umpan',
		'feed_per_category' => 'Umpan per kategori',
		'idle' => 'Umpan Tak Terbarukan',
		'main' => 'Statistik utama',
		'main_stream' => 'Bagian utama',
		'nb_unreads' => 'Jumlah artikel belum dibaca',
		'no_idle' => 'Tidak ada umpan tak terbarukan!',
		'number_entries' => '%d artikel',
		'overview' => 'Ringkasan',
		'percent_of_total' => '% dari total',
		'repartition' => 'Pengkategorian artikel: %s',
		'status_favorites' => 'Favorit',
		'status_read' => 'Terbaca',
		'status_total' => 'Total',	// IGNORE
		'status_unread' => 'Belum Terbaca',
		'title' => 'Statistik',
		'top_feed' => 'Sepuluh umpan teratas',
		'unread_dates' => 'Tanggal dengan artikel belum dibaca terbanyak',
	),
	'system' => array(
		'_' => 'Konfigurasi Sistem',
		'auto-update-url' => 'URL server untuk pembaruan otomatis',
		'base-url' => array(
			'_' => 'URL server',
			'recommendation' => 'Rekomendasi Otomatis: <kbd>%s</kbd>',
		),
		'closed_registration_message' => 'Pesan jika pendaftaran ditutup',
		'cookie-duration' => array(
			'help' => 'dalam detik',
			'number' => 'Durasi untuk terus masuk',
		),
		'default_closed_registration_message' => 'Server ini tidak menerima pendaftaran baru untuk saat ini.',
		'force_email_validation' => 'Paksa verifikasi alamat surel',
		'instance-name' => 'Nama server',
		'internal-host-allowlist' => array(
			'_' => 'Daftar izin host internal',
			'help' => 'Satu entri per baris:<ul><li>Sebuah <code>host:port</code>. Misalnya <code>127.0.0.1:8080</code> atau <code>rss-bridge:80</code></li><li>Notasi CIDR. Misalnya <code>0.0.0.0/0</code> untuk mengizinkan semua IPv4, <code>::/0</code> untuk mengizinkan semua IPv6</li><li>Sebuah <code>*</code> untuk mengizinkan semua host (tidak aman)</li></ul>',
		),
		'max-categories' => 'Jumlah kategori maksimal per pengguna',
		'max-feeds' => 'Jumlah umpan maksimal per pengguna',
		'override-by-env-var' => 'Pengaturan ini ditetapkan oleh variabel lingkungan <kbd>%s</kbd>.',
		'registration' => array(
			'number' => 'Jumlah akun maksimal',
			'select' => array(
				'label' => 'Formulir pendaftaran',
				'option' => array(
					'noform' => 'Nonaktif: Tidak ada formulir pendaftaran',
					'nolimit' => 'Aktif: Tidak ada batasan akun',
					'setaccountsnumber' => 'Atur jumlah akun maksimal',
				),
			),
			'status' => array(
				'disabled' => 'Formulir dinonaktifkan',
				'enabled' => 'Formulir diaktifkan',
			),
			'title' => 'Formulir Pendaftaran Pengguna',
		),
		'sensitive-parameter' => 'Konfigurasi sensitif. Sunting manual di <kbd>./data/config.php</kbd>',
		'tos' => array(
			'disabled' => 'Tidak diberikan',
			'enabled' => '<a href="./?a=tos">diaktifkan</a>',
			'help' => 'Cara <a href="https://freshrss.github.io/FreshRSS/en/admins/12_User_management.html#enable-terms-of-service-tos" target="_blank">mengaktifkan Ketentuan Layanan.</a>',
		),
		'websub' => array(
			'help' => 'Tentang <a href="https://freshrss.github.io/FreshRSS/en/users/WebSub.html" target="_blank">WebSub</a>',
		),
	),
	'update' => array(
		'_' => 'Pembaruan FreshRSS',
		'apply' => 'Mulai pembaruan',
		'changelog' => 'Log Perubahan',
		'check' => 'Periksa pembaruan baru',
		'copiedFromURL' => 'update.php disalin dari %s ke ./data',
		'current_version' => 'Versi saat ini adalah',
		'last' => 'Terakhir diperiksa pada',
		'loading' => 'Memperbarui…',
		'none' => 'Tidak ada pembaruan yang baru',
		'releaseChannel' => array(
			'_' => 'Kanal rilis',
			'edge' => 'Rilis Baru (“edge”)',
			'latest' => 'Rilis Stabil (“latest”)',	// DIRTY - Versioned release (“latest”)
		),
		'title' => 'Pembaruan FreshRSS',
		'viaGit' => 'Pembaruan lewat git dan Github.com dimulai',
	),
	'user' => array(
		'admin' => 'Administrator',	// IGNORE
		'article_count' => 'Artikel',
		'back_to_manage' => '← Kembali ke Daftar Pengguna',
		'create' => 'Buat pengguna baru',
		'database_size' => 'Ukuran basis data',
		'email' => 'Alamat surel',
		'enabled' => 'Aktif',
		'feed_count' => 'Umpan',
		'is_admin' => 'Admin',
		'language' => 'Bahasa',
		'last_user_activity' => 'Aktivitas pengguna terakhir',
		'list' => 'Daftar pengguna',
		'number' => 'Ada %d akun yang telah dibuat',
		'numbers' => 'Ada %d akun yang telah dibuat',
		'password_form' => 'Kata sandi<br /><small>(Untuk metode masuk formulir web)</small>',
		'password_format' => 'Setidaknya 7 karakter',
		'title' => 'Kelola pengguna',
		'username' => 'Nama pengguna',
	),
);
