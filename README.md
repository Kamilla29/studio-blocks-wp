# Studio Blocks — WordPress + React Integration

A portfolio-oriented **WordPress Gutenberg plugin** demonstrating how React/TypeScript application patterns can be integrated with PHP, WordPress content and the REST API.

The project is deliberately different from a normal React SPA. Its purpose is to show CMS-oriented frontend engineering: custom Gutenberg blocks, server rendering, typed React interactions, WordPress data models and a clean PHP ↔ React boundary.

## What it contains

### 1. Studio Service Grid

A dynamic Gutenberg block for commercial service cards.

- React + TypeScript editor controls;
- custom `studio_service` post type with REST support;
- server-side PHP rendering with `WP_Query`;
- editor-controlled heading, intro, columns and item limit;
- semantic card markup and responsive layout;
- content remains editable in WordPress instead of being hard-coded in React.

### 2. Studio Lead Form

A dynamic block with a React frontend island mounted into PHP-rendered markup.

- React Hook Form field state;
- Zod schema validation;
- `@hookform/resolvers` integration;
- TanStack Query mutation for submission;
- custom `/wp-json/studio-blocks/v1/leads` endpoint;
- PHP sanitization and second validation layer;
- private `studio_lead` storage in WordPress;
- accessible errors, focus states and live submission feedback;
- honeypot plus short transient rate limiting;
- translation-ready PHP and JavaScript strings.

## Stack

**CMS / backend**  
WordPress · Gutenberg · PHP 8.1+ · WordPress REST API · Custom Post Types

**Frontend**  
React · TypeScript · TanStack Query · React Hook Form · Zod · SCSS

**Quality / delivery**  
GitHub Actions · Docker Compose · PHP syntax validation · automated Gutenberg build

The package versions are pinned to current September 2026 releases for the main frontend libraries and `@wordpress/scripts`.

## Architecture

The project demonstrates two important WordPress integration patterns:

1. **React editor → PHP frontend** for content-oriented dynamic blocks.
2. **PHP shell → React island → REST API** for richer frontend interactions.

See [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) for the data flow and design decisions.

## Local setup

Requirements:

- Node.js 22+
- npm
- Docker / Docker Compose

Install and build the Gutenberg assets:

```bash
npm install
npm run build
```

Start WordPress and MySQL:

```bash
docker compose up -d
```

Open:

```text
http://localhost:8080
```

Complete the WordPress installer, then activate **Studio Blocks** under Plugins.

To seed demo service content after completing the WordPress installer, use the included WP-CLI service:

```bash
docker compose run --rm cli eval-file wp-content/plugins/studio-blocks/scripts/seed-services.php
```

Then add **Studio Service Grid** and **Studio Lead Form** blocks in the Gutenberg editor.

## Development

Watch Gutenberg assets:

```bash
npm run start
```

Production build:

```bash
npm run build
```

JavaScript linting:

```bash
npm run lint:js
```

SCSS linting:

```bash
npm run lint:css
```

PHP syntax check:

```bash
find . -name '*.php' -not -path './node_modules/*' -print0 | xargs -0 -n1 php -l
```

## Repository structure

```text
studio-blocks-wp/
├── .github/workflows/quality.yml
├── docs/
│   ├── ARCHITECTURE.md
│   └── PORTFOLIO_NOTES.md
├── includes/
│   ├── class-studio-blocks-plugin.php
│   └── class-studio-blocks-rest-controller.php
├── languages/studio-blocks.pot
├── scripts/seed-services.php
├── src/blocks/
│   ├── lead-form/
│   └── service-grid/
├── docker-compose.yml
├── package.json
├── studio-blocks.php
└── tsconfig.json
```

## Engineering notes

The public lead endpoint intentionally does **not** trust client validation. Zod handles UX-side validation, while the PHP controller independently sanitizes and validates the request before storage.

The project also avoids turning every WordPress surface into a client-rendered React app. The service grid is rendered on the server because its content is SEO-relevant and does not need runtime JavaScript. React is used where editor experience or frontend interactivity benefits from it.

## Portfolio context

This is a fictional studio plugin created as a technical portfolio case. It is designed to demonstrate WordPress/Gutenberg work alongside my existing React/TypeScript application projects.

For concise CV/LinkedIn copy, see [`docs/PORTFOLIO_NOTES.md`](docs/PORTFOLIO_NOTES.md).

---

**Kamilla Kuanysheva**  
React / Frontend Developer · TypeScript · WordPress Integration
