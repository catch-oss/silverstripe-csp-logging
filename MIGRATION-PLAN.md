# Migration Plan: silverstripe-csp-logging

## Summary

- **Package**: camspiers/silverstripe-csp-logging
- **Type**: B (Silverstripe module)
- **Tier**: 2
- **Risk Level**: Low
- **Estimated Scope**: 3 source files, 3 classes, 1 test file

## Change Inventory

### Namespace Renames Required

No SS5→SS6 namespace renames needed. The module uses:
- `SilverStripe\Control\Controller` (unchanged in SS6)
- `SilverStripe\Control\HTTPRequest` (unchanged in SS6)
- `SilverStripe\Control\HTTPResponse` (unchanged in SS6)
- `SilverStripe\Control\Director` (used in YAML routes, unchanged)
- `SilverStripe\Core\Injector\Injector` (used in YAML config, unchanged)

### Composer Dependency Changes

| Package | Current Version | Target Version |
|---|---|---|
| php | (unspecified) | ^8.5 |
| psr/log | ^3.0 | ^3.0 (unchanged) |
| monolog/monolog | (not declared, used transitively) | ^3.2 (add as explicit dep) |
| silverstripe/framework | >=4 (dev) | ^6.0 (dev) |
| phpunit/phpunit | ~3.7 (dev) | ^11.0 (dev) |
| symfony/class-loader | ~2.3 (dev) | (remove - abandoned) |

### API Changes Required

| Pattern | Migration | Files Affected |
|---|---|---|
| `public $logger` property | Add explicit type declaration | src/Controller.php |
| `format(LogRecord $record)` | Add `: string` return type | src/LogFormatter.php |
| `formatBatch(array $records)` | Add `: string` return type | src/LogFormatter.php |
| `index(HTTPRequest $request)` | Add `: HTTPResponse` return type | src/Controller.php |

### PHP 8.5 Compatibility Fixes

| Issue | Fix | Files Affected |
|---|---|---|
| Missing return type on `format()` | Add `: string` | src/LogFormatter.php |
| Missing return type on `formatBatch()` | Add `: string` | src/LogFormatter.php |
| Missing return type on `index()` | Add `: HTTPResponse` return type | src/Controller.php |
| Untyped `$logger` property | Add `Logger` type declaration | src/Controller.php |

### PHPUnit Migration

| Issue | Fix | Files Affected |
|---|---|---|
| PHPUnit ~3.7 dependency | Upgrade to ^11.0 | composer.json |
| phpunit.xml.dist uses removed attributes | Rewrite for PHPUnit 11 schema | phpunit.xml.dist |
| `backupStaticAttributes` removed | Remove attribute | phpunit.xml.dist |
| `convertErrorsToExceptions` removed | Remove attribute | phpunit.xml.dist |
| `convertNoticesToExceptions` removed | Remove attribute | phpunit.xml.dist |
| `convertWarningsToExceptions` removed | Remove attribute | phpunit.xml.dist |
| `syntaxCheck` removed | Remove attribute | phpunit.xml.dist |
| Test uses `\SS_HTTPResponse` (SS3 class) | Use `SilverStripe\Control\HTTPResponse` | tests/ControllerTest.php |
| Test uses `\SS_HTTPRequest` (SS3 class) | Use `SilverStripe\Control\HTTPRequest` | tests/ControllerTest.php |
| Test bootstrap uses abandoned `symfony/class-loader` | Rewrite to use composer autoload | tests/bootstrap.php |
| Test creates Controller with wrong constructor args | Fix to match SS Controller pattern | tests/ControllerTest.php |

### Config Changes

| File | Change Required |
|---|---|
| _config/routes.yml | No changes needed |
| _config/log.yml | Update `Name` key casing (lowercase `name:` → `Name:`), update log format to Catch standard |

## Risk Assessment

| Area | Risk | Notes |
|---|---|---|
| Namespace renames | Low | No SS5→SS6 namespace changes needed |
| API changes | Low | Minimal surface area, 3 small classes |
| PHP 8.5 compat | Low | Only missing return types and property types |
| Test migration | Medium | Tests use SS3-era classes (SS_HTTPRequest/Response), need full rewrite |
| Config changes | Low | Routes unchanged, log config minor tweaks |
| Composer deps | Low | Straightforward version bumps |

## Migration Steps (Ordered)

### Phase 1: composer.json
- [ ] Add `"php": "^8.5"` to require
- [ ] Add `"monolog/monolog": "^3.2"` to require (currently only transitive)
- [ ] Update `"silverstripe/framework"` to `"^6.0"` in require-dev
- [ ] Update `"phpunit/phpunit"` to `"^11.0"` in require-dev
- [ ] Remove `"symfony/class-loader"` from require-dev (abandoned)
- [ ] Add `"silverstripe/vendor-plugin": "^2.0"` to require
- [ ] Run composer validate

### Phase 2: Namespace Renames
- [ ] No namespace renames required (all used namespaces are stable in SS6)

### Phase 3: API Changes
- [ ] Add `Logger` type to `$logger` property in Controller.php
- [ ] Add `: HTTPResponse` return type to `Controller::index()`
- [ ] Add `: string` return type to `LogFormatter::format()`
- [ ] Add `: string` return type to `LogFormatter::formatBatch()`

### Phase 4: PHP 8.5 Compatibility
- [ ] All fixes covered in Phase 3 (return types, property types)

### Phase 5: Logging Integration
- [ ] Update `LogFormatter` to use Catch log format: `[%d] %p %c - %m`
- [ ] Update `_config/log.yml` to use Catch logging standard channel name
- [ ] Fix `Name:` casing in `_config/log.yml` YAML header

### Phase 6: Config Updates
- [ ] Verify `_config/routes.yml` works with SS6 Director
- [ ] Verify `_config/log.yml` Injector config is SS6-compatible

### Phase 7: Test Suite
- [ ] Rewrite `phpunit.xml.dist` for PHPUnit 11 schema
- [ ] Rewrite `tests/bootstrap.php` to use composer autoload only
- [ ] Rewrite `tests/ControllerTest.php` to use SS6 classes (HTTPRequest, HTTPResponse)
- [ ] Add `: void` return types to test setUp/tearDown if added
- [ ] Target 80% coverage

## Dependencies

- Depends on: none (Tier 2 has no internal dependencies from Tier 1 for this module)
- Blocks: none directly (no higher-tier repos depend on this package)
