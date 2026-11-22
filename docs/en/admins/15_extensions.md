# Extensions

Not every feature is relevant for all users, but some special features are relevant to the right person. That is why FreshRSS is extendable.

There are some "official" extensions (supported and published by the FreshRSS development team and community) and "community" extensions (developed and published individually by third-party developers).

## Extension repositories

Open **Configuration → Extensions** to browse the catalogue.
The **Official** section lists extensions from [FreshRSS/Extensions](https://github.com/FreshRSS/Extensions).
The **Unofficial** section lists independently maintained extensions.
The privacy preference for retrieving the extension list controls catalogue access.
**Refresh catalogue** reloads the catalogue and updates the displayed versions.
Local extensions remain available when catalogue retrieval is disabled.

## Marketplace

Administrators can install and update approved versions, and uninstall third-party extensions for the entire instance.
FreshRSS pins approved versions to a source commit and an archive checksum.
An unapproved or incompatible version remains visible with an explanation.
For unofficial packages, check the displayed repository before confirming.
Extensions contain PHP code that FreshRSS loads while discovering installed extensions.

Installation leaves the extension disabled until it is explicitly enabled.
User extensions can be enabled or disabled by each user. System extensions are managed by administrators.
**Update** replaces an approved marketplace installation while retaining its configuration and activation state.
FreshRSS requires a verified marketplace installation before offering an update.
Uninstalling requires confirmation and removes the files for every user.

For Docker, mount a persistent volume at `/var/www/FreshRSS/extensions` alongside the data volume.
The extensions volume must be writable by the web server for marketplace operations.
A read-only extensions volume still allows catalogue browsing and the management of activation and user settings.
Keep the hidden `.marketplace` directory with the extension volume so FreshRSS can recover an interrupted update.

## How to install manually

Upload the folder (f.e. `CustomCSS`) of your chosen extension into your `./extensions` directory.

Result: Content of `./extensions/CustomCSS/` has f.e. `extension.php`, `metadata.json`, `configure.php`, `README.md` files and the folders `i18n` and `static`

Important: Do not delete or overwrite the existing files `./extensions/.gitignore` and `./extensions/README.md`.

> **Note:** If `THIRDPARTY_EXTENSIONS_PATH` is set in `data/constants.local.php`, extensions must be placed in that directory instead of `./extensions`. If extensions are not appearing in the UI, check whether this constant is configured.

## How to enable/disable and manage

See in the front end: configuration menu `Configuration/Extensions`

### User extensions

Every user has to manage the extensions by themselves. Configuration via the gear icon is valid only for that user, not for other users.

metadata.json:

```json
{
  "type": "user"
}
```

### System extensions

Only administrators can enable/disable system extensions. The configuration via the gear icon is valid for every user.

metadata.json:

```json
{
  "type": "system"
}
```

### pre installed extensions (core extensions)

See folder: `./lib/core-extensions`

* `UserCSS`: [User CSS](../users/05_Configuration.md#user-css)
* `UserJS`: [User JS](../users/05_Configuration.md#user-js)

Important: do not install your chosen extensions here!
