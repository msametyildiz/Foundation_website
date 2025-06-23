# NECAT DERNEĞİ - Project Structure

This document describes the reorganized project structure for better maintainability and development workflow.

## Directory Structure

```
necat_dernegi_site/
├── admin/                      # Admin panel files
│   ├── includes/
│   └── pages/
├── ajax/                       # AJAX endpoints
├── assets/                     # Static assets (CSS, JS, images)
│   ├── css/
│   ├── images/
│   └── js/
├── backups/                    # Backup files
├── config/                     # Configuration files
├── database/                   # Database files and migrations
│   ├── backups/
│   ├── migrations/
│   ├── seeders/
│   └── seeds/
├── development/                # Development tools and scripts
│   ├── scripts/               # Setup, deployment, validation scripts
│   └── tools/                 # Development utilities
├── docs/                       # Documentation
│   ├── api/
│   ├── completion-reports/    # Project completion reports
│   ├── deployment/
│   ├── reports/
│   └── setup/
├── includes/                   # Shared PHP includes
├── logs/                       # Application logs
├── pages/                      # Main website pages
├── resources/                  # Resource files
│   └── views/
├── scripts/                    # Production scripts
│   ├── deployment/
│   ├── optimization/
│   ├── setup/
│   └── validation/
├── sql/                        # SQL scripts and queries
├── temp/                       # Temporary files
├── tests/                      # All test files (CENTRALIZED)
│   ├── database/              # Database tests
│   ├── debug/                 # Debug and manual test files
│   ├── email/                 # Email system tests
│   ├── forms/                 # Form functionality tests
│   ├── functional/            # Functional tests
│   ├── integration/           # Integration tests
│   ├── ui/                    # UI/Frontend tests
│   ├── unit/                  # Unit tests
│   └── run_tests.php          # Test runner script
├── uploads/                    # User uploaded files
└── vendor/                     # Composer dependencies
```

## Key Improvements

### 1. Centralized Testing
- All test files are now organized under `tests/` directory
- Debug files moved to `tests/debug/`
- Created `tests/run_tests.php` for running all tests
- Organized tests by type (unit, integration, functional, etc.)

### 2. Development Tools Organization
- Created `development/` directory for development-only files
- Moved setup scripts to `development/scripts/`
- Moved utilities to `development/tools/`

### 3. Documentation Consolidation
- Moved completion reports to `docs/completion-reports/`
- Organized documentation by type
- Centralized all markdown files in `docs/`

### 4. Clean Root Directory
- Removed loose files from root
- Better separation of concerns
- Easier navigation and maintenance

## Running Tests

To run all tests:
```bash
php tests/run_tests.php
```

To run specific test types:
```bash
# Run only unit tests
php tests/run_tests.php --type=unit

# Run only integration tests  
php tests/run_tests.php --type=integration
```

## Development Workflow

1. **For new features**: Add tests in appropriate `tests/` subdirectory
2. **For debugging**: Use files in `tests/debug/`
3. **For deployment**: Use scripts in `development/scripts/`
4. **For documentation**: Add to `docs/` with appropriate subdirectory

## File Naming Conventions

- Test files: `test_*.php`
- Debug files: `debug_*.php`
- Setup scripts: `setup_*.php` or `setup_*.sh`
- Validation scripts: `validate_*.sh`

This structure promotes:
- Better code organization
- Easier testing and debugging
- Cleaner development workflow
- Better maintainability
- Easier onboarding for new developers
