# NECAT DERNEĞİ Website

Modern, responsive website for NECAT Derneği (Association) built with PHP, MySQL, and modern web technologies.

## 🚀 Quick Start

```bash
# Clone the repository
git clone [repository-url]
cd necat_dernegi_site

# Install dependencies
composer install

# Set up database
mysql -u root -p < database/necat_dernegi.sql

# Configure database connection
cp config/database.php.example config/database.php
# Edit config/database.php with your database credentials

# Run tests
./tests/run_tests.sh

# Start development server
php -S localhost:8000
```

## 📁 Project Structure

The project has been reorganized for better maintainability:

```
necat_dernegi_site/
├── admin/                  # Admin panel
├── ajax/                   # AJAX endpoints  
├── assets/                 # Static assets (CSS, JS, images)
├── config/                 # Configuration files
├── database/               # Database files and migrations
├── development/            # Development tools and scripts
├── docs/                   # Documentation
├── includes/               # Shared PHP includes
├── pages/                  # Main website pages
├── tests/                  # All tests (centralized)
├── uploads/                # User uploads
└── vendor/                 # Dependencies
```

## 🧪 Testing

We have a comprehensive testing system with all tests centralized in the `tests/` directory:

### Run All Tests
```bash
./tests/run_tests.sh
```

### Run Specific Test Types
```bash
# Unit tests
./tests/run_tests.sh --type=unit

# Integration tests  
./tests/run_tests.sh --type=integration

# Form tests
./tests/run_tests.sh --type=forms

# Database tests
./tests/run_tests.sh --type=database

# Email tests
./tests/run_tests.sh --type=email

# UI tests
./tests/run_tests.sh --type=ui
```

### Debug Tests
Debug and manual test files are located in `tests/debug/`:
- `debug_email.php` - Email system debugging
- `debug_volunteer_email.php` - Volunteer form email debugging
- `direct_email_test.php` - Direct email testing
- `simple_email_test.php` - Simple email functionality test

## 🛠️ Development

### Development Tools
Located in `development/` directory:

**Scripts** (`development/scripts/`):
- `setup_email_settings.php` - Email configuration setup
- `setup_projects_hero.php` - Projects hero section setup
- `deploy.sh` - Deployment script
- `validate_*.sh` - Various validation scripts

**Tools** (`development/tools/`):
- `get_logo_base64.php` - Logo base64 encoder
- `create_admin_logs.php` - Admin logging setup
- `db_admin.php` - Database administration

### Adding New Features

1. **Write tests first** in appropriate `tests/` subdirectory
2. **Implement feature** in relevant directory
3. **Run tests** to ensure everything works
4. **Update documentation** if needed

### Code Quality

- Follow PSR-4 autoloading standards
- Write tests for new functionality
- Use proper error handling
- Document complex functions

## 📚 Documentation

Documentation is organized in the `docs/` directory:

- `docs/api/` - API documentation
- `docs/completion-reports/` - Project completion reports
- `docs/deployment/` - Deployment guides
- `docs/setup/` - Setup instructions

## 🔧 Configuration

Key configuration files:
- `config/database.php` - Database connection
- `tests/config.php` - Test configuration
- `composer.json` - PHP dependencies

## 🚀 Deployment

Use the deployment script:
```bash
./development/scripts/deploy.sh
```

Or follow manual deployment steps in `docs/deployment/`.

## 📄 License

[Add your license information here]

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Write tests for new functionality
4. Implement the feature
5. Run tests to ensure everything works
6. Submit a pull request

## 📞 Support

For support, please contact [your contact information]

---

For detailed project structure information, see [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md).
