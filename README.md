# SilverStripe Content-Security-Policy Logging

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
