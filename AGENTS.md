## Cursor Cloud specific instructions

This is an empty test repository ("Тестовый репозиторий") containing only a `.gitkeep` placeholder file. There are no application services, dependencies, or build steps to run yet.

### Available tools

| Tool | Version | Notes |
|---|---|---|
| Node.js | v22 | via nvm |
| Python | 3.12 | system |
| PHP | 8.3 | with extensions: mbstring, xml, curl, zip, json, PDO, openssl |
| Composer | 2.9 | PHP dependency manager |
| Git | 2.43 | configured |

- PHP was installed via `apt` (`php php-cli php-common php-mbstring php-xml php-curl php-zip`). If the VM is recreated, the update script reinstalls it.
- Composer was installed globally to `/usr/local/bin/composer`.
- **No lint, test, or build commands** exist yet. When code is added, update this section accordingly.
