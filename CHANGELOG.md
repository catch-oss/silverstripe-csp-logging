# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed
- Upgraded to Silverstripe 6 compatibility
- Updated PHP requirement to ^8.5
- Migrated test suite to PHPUnit 11
- Added explicit type declarations to all properties and methods
- Updated Monolog channel to dot-notation format (`Camspiers.CSP`)

### Added
- Catch logging standard integration (`[%d] %p %c - %m` format)
- LogFormatterTest and LoggerTest for improved coverage
- MIGRATION-PLAN.md documenting all migration changes
- Compatibility table in README.md

### Removed
- Abandoned `symfony/class-loader` dev dependency
- Legacy PHPUnit configuration attributes
- SS3-era test classes (`SS_HTTPRequest`, `SS_HTTPResponse`)
- Travis CI configuration (replaced by GitHub Actions)
