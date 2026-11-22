<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ExtensionCatalogTest extends TestCase {
	public function testCanonicalOfficialRepository(): void {
		$official = FreshRSS_ExtensionCatalog::OFFICIAL_REPOSITORY;
		self::assertSame($official, FreshRSS_ExtensionCatalog::repository('https://github.com/FreshRSS/Extensions'));
		self::assertSame($official, FreshRSS_ExtensionCatalog::repository('https://github.com/FRESHRSS/EXTENSIONS.git/'));
		self::assertNotSame($official, FreshRSS_ExtensionCatalog::repository('https://github.com/FreshRSS/Extensions-Fork'));
		self::assertNotSame($official, FreshRSS_ExtensionCatalog::repository('https://github.com/FreshRSS/Extensions/tree/main'));
		self::assertNotSame($official, FreshRSS_ExtensionCatalog::repository('https://github.com.example/FreshRSS/Extensions'));
	}

	public function testValidatesWholeCatalogue(): void {
		self::assertNull(FreshRSS_ExtensionCatalog::parse('{"extensions":{}}'));
		self::assertNull(FreshRSS_ExtensionCatalog::parse('{"extensions":[null]}'));
		self::assertSame([], FreshRSS_ExtensionCatalog::parse('{"extensions":[]}'));

		$entry = self::entry();
		$parsed = FreshRSS_ExtensionCatalog::parse(json_encode(['extensions' => [$entry]], JSON_THROW_ON_ERROR));
		self::assertNotNull($parsed);
		self::assertSame('1.2', $parsed[0]['version']);
		self::assertTrue(FreshRSS_ExtensionCatalog::compatible($parsed[0]));

		$invalid = array_replace($entry, ['url' => 'javascript:void(0)']);
		self::assertNull(FreshRSS_ExtensionCatalog::parse(json_encode(['extensions' => [$entry, $invalid]], JSON_THROW_ON_ERROR)));
	}

	public function testMatchingManualAndAmbiguousCopiesCannotBeReplaced(): void {
		$parsed = FreshRSS_ExtensionCatalog::parse(json_encode(['extensions' => [self::entry()]], JSON_THROW_ON_ERROR));
		self::assertNotNull($parsed);
		$entry = $parsed[0];
		$manual = ['/manual/Example' => ['entrypoint' => 'Example', 'version' => '1.1']];
		self::assertSame('origin', FreshRSS_ExtensionMarketplace::status($entry, $parsed, $manual)['reason']);
		self::assertSame('ambiguous', FreshRSS_ExtensionMarketplace::status($entry, [$entry, $entry], [])['reason']);
	}

	/** @return array<string,string|float> */
	private static function entry(): array {
		return [
			'name' => 'Example',
			'entrypoint' => 'Example',
			'author' => 'FreshRSS',
			'description' => 'Example extension',
			'version' => 1.2,
			'type' => 'user',
			'url' => 'https://github.com/FreshRSS/Extensions',
			'method' => 'git',
			'directory' => 'xExtension-Example',
		];
	}
}
