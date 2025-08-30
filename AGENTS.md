# AGENTS

## Project overview
- PHP-based frontend for the Tugo review and flash-sale platform.
- Directory structure:
  - `pages/` – individual screens (home, login, profile, flash sale, etc.).
  - `includes/` – shared partials such as header, footer, navbar, tracking scripts.
  - `helpers/` – utility functions (API caller, session/token helpers, time helpers).
  - `assets/` – static resources (`css/`, `js/`, `images`).
  - `db/` – sample SQL and UML.
  - `.env` (not tracked) – contains `BASE_URL`, `DOMAIN_URL`, `VERSION`.

## Coding guidelines
- PHP files begin with `<?php` and close with `?>` only when HTML follows.
- Use `APICaller` for all HTTP interactions; initialize with `APICaller::init()`.
- Keep tokens in `$_SESSION['jwt_token']`; access via `get_access_token()`.
- Shared HTML fragments belong in `includes/` and are pulled in with `include`.
- Environment-dependent values (API domain, image host, version) are loaded from `.env` using helper functions.
- CSS and JS are page‑specific; update asset references in `includes/header.php` when adding new pages.
- Follow existing naming: `snake_case` for PHP variables/functions, `kebab-case` for CSS classes, and `camelCase` for JavaScript.
- For new pages, provide:
  1. `$page_title` variable.
  2. `include '../includes/header.php';` and `include '../includes/footer.php';`.
  3. Navigation through `includes/navbar.php` when appropriate.

## Contributing workflow
- Maintain parity with existing API endpoints; consult backend team before changes.
- Keep user-facing text in Vietnamese unless specified.
- Test PHP pages in a local LAMP/LNMP environment with `.env` populated.
- Track analytics scripts (Google Tag, Meta Pixel) as in `includes/tracking.php`.
