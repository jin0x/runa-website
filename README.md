# Runa Theme

WordPress theme built with [Roots Sage](https://roots.io/sage/), Laravel Blade, and Tailwind CSS.

## Requirements

- Node.js >= 20.0.0
- PHP >= 8.2
- Composer

## Local Development

```bash
# Install dependencies
composer install
npm install

# Start development server
npm run dev

# Build for production
npm run build
```

## Deployment Workflow

This project uses GitHub Actions for automated deployments to WP Engine.

### Branch Strategy

| Branch    | Environment              | URL                  |
|-----------|--------------------------|----------------------|
| `main`    | Staging                  | staging.runa.io      |
| `release` | Production               | runa.io              |

### How It Works

1. **Development**: Create feature branches from `main` for new work
2. **Staging**: Merge PRs into `main` → automatically deploys to staging.runa.io
3. **Production**: Merge `main` into `release` → automatically deploys to runa.io

### Deployment Process

When code is pushed to `main` or `release`, GitHub Actions will:

1. Checkout the code
2. Install PHP dependencies (`composer install`)
3. Install Node dependencies (`npm ci`)
4. Build assets (`npm run build`)
5. Run PHP linting
6. Deploy to WP Engine via rsync
7. Clear WP Engine cache

### Branch Protection (Recommended)

For both `main` and `release` branches:

- Require pull request reviews before merging
- Require status checks to pass (CI must be green)
- Disallow force pushes

For `release` branch (production):

- Require 2 approvals before merging

### Workflow

```
feature/new-feature
        │
        ▼
      main  ──────────► staging.runa.io
        │
        ▼
    release ──────────► runa.io
```

### Files

- `.github/workflows/staging.yml` - Staging deployment workflow
- `.github/workflows/production.yml` - Production deployment workflow
- `.deployignore` - Files excluded from deployment

## Theme Structure

```
├── app/                 # PHP application code
├── config/              # Theme configuration
├── public/              # Built assets (generated)
├── resources/           # Source assets and views
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript
│   └── views/          # Blade templates
├── .github/workflows/   # GitHub Actions
├── composer.json        # PHP dependencies
├── package.json         # Node dependencies
└── vite.config.js       # Vite configuration
```
