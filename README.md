# SilverStripe Content-Security-Policy Logging

<!-- PROJECT SHIELDS -->
[![SonarCloud](https://github.com/catch-oss/silverstripe-csp-logging/actions/workflows/sonar.yml/badge.svg)](https://github.com/catch-oss/silverstripe-csp-logging/actions/workflows/sonar.yml)
[![Test](https://github.com/catch-oss/silverstripe-csp-logging/actions/workflows/test.yml/badge.svg)](https://github.com/catch-oss/silverstripe-csp-logging/actions/workflows/test.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=catch-oss_silverstripe-csp-logging)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=bugs)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=code_smells)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=coverage)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Duplicated Lines Density](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=duplicated_lines_density)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=ncloc)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=reliability_rating)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=security_rating)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=sqale_index)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=sqale_rating)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=catch-oss_silverstripe-csp-logging&metric=vulnerabilities)](https://sonarcloud.io/component_measures?id=catch-oss_silverstripe-csp-logging)

Allows the logging of CSP violations in SilverStripe.

## Compatibility

| Branch | Silverstripe | PHP |
|--------|-------------|-----|
| release/6 | ^6.0 | ^8.5 |
| release/5 | ^5.1 | ~8.4 |

## Installation

```bash
composer require camspiers/silverstripe-csp-logging
```

## Usage

1. Set your `Content-Security-Policy` headers
2. Add `report-uri /csp-report/;` to the header to log violations through SilverStripe

The module registers a route at `/csp-report` and logs violations via Monolog using the Injector configuration in `_config/log.yml`. The default configuration writes to `../log/csp.log`.

### Custom Logger Configuration

Override the logger in your project's YAML config:

```yaml
SilverStripe\Core\Injector\Injector:
  Camspiers\CSP\Logger:
    type: singleton
    class: Camspiers\CSP\Logger
    constructor:
      - 'Camspiers.CSP'
    calls:
      LogFileHandler: [pushHandler, ['%$YourCustomHandler']]
```
