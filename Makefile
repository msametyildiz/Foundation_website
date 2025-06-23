# NECAT DERNEĞİ Development Makefile
# Makes common development tasks easier

.PHONY: help test test-unit test-integration test-forms test-database test-email test-ui install setup clean deploy

# Default target
help:
	@echo "NECAT DERNEĞİ Development Commands"
	@echo "=================================="
	@echo ""
	@echo "Testing:"
	@echo "  test              Run all tests"
	@echo "  test-unit         Run unit tests only"
	@echo "  test-integration  Run integration tests only" 
	@echo "  test-forms        Run form tests only"
	@echo "  test-database     Run database tests only"
	@echo "  test-email        Run email tests only"
	@echo "  test-ui           Run UI tests only"
	@echo ""
	@echo "Setup:"
	@echo "  install           Install dependencies"
	@echo "  setup             Set up development environment"
	@echo "  clean             Clean temporary files"
	@echo ""
	@echo "Deployment:"
	@echo "  deploy            Deploy to production"
	@echo ""
	@echo "Utilities:"
	@echo "  logs              Show recent logs"
	@echo "  status            Show project status"
	@echo "  dashboard         Show comprehensive project dashboard"
	@echo "  fix-paths         Fix file paths after reorganization"

# Testing targets
test:
	@echo "Running all tests..."
	./tests/run_tests.sh

test-unit:
	@echo "Running unit tests..."
	./tests/run_tests.sh --type=unit

test-integration:
	@echo "Running integration tests..."
	./tests/run_tests.sh --type=integration

test-forms:
	@echo "Running form tests..."
	./tests/run_tests.sh --type=forms

test-database:
	@echo "Running database tests..."
	./tests/run_tests.sh --type=database

test-email:
	@echo "Running email tests..."
	./tests/run_tests.sh --type=email

test-ui:
	@echo "Running UI tests..."
	./tests/run_tests.sh --type=ui

# Setup targets
install:
	@echo "Installing dependencies..."
	composer install

setup: install
	@echo "Setting up development environment..."
	@mkdir -p logs temp/test uploads/test
	@chmod 755 tests/run_tests.sh
	@echo "Development environment ready!"

clean:
	@echo "Cleaning temporary files..."
	@rm -rf temp/test/*
	@rm -rf logs/test/*
	@rm -rf uploads/test/*
	@echo "Cleaned temporary files"

# Deployment
deploy:
	@echo "Deploying to production..."
	./development/scripts/deploy.sh

# Utility targets
logs:
	@echo "Recent logs:"
	@tail -n 20 logs/*.log 2>/dev/null || echo "No logs found"

status:
	@echo "Project Status:"
	@echo "==============="
	@echo "PHP Version: $(shell php -v | head -n 1)"
	@echo "Composer: $(shell composer --version 2>/dev/null || echo 'Not installed')"
	@echo "Database: $(shell mysql --version 2>/dev/null || echo 'Not available')"
	@echo "Test Files: $(shell find tests -name '*.php' | wc -l)"
	@echo "Last Modified: $(shell ls -lt | head -n 2 | tail -n 1 | awk '{print $$6, $$7, $$8, $$9}')"

dashboard:
	@echo "Opening project dashboard..."
	./development/tools/project_dashboard.sh

fix-paths:
	@echo "Fixing file paths..."
	php development/tools/fix_paths.php

# Development server
serve:
	@echo "Starting development server on http://localhost:8000"
	php -S localhost:8000
