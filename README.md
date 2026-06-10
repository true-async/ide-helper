# TrueAsync IDE helper

IDE help files for the [TrueAsync](https://github.com/true-async/php-async) ecosystem (PHP 8.6+). Enables autocompletion, inline docs, and strict type analysis for PhpStorm, PHPStan, and Psalm.

Bundled stubs:

- **`Async\*`** — the [`async`](https://github.com/true-async/php-async) extension (coroutines, channels, threads, futures, scopes, pools) plus the global `Cancellation` base class and the pooled `PDO`.
- **`TrueAsync\*`** — the [HTTP server](https://github.com/true-async/server) extension (`HttpServer`, `HttpRequest`, `HttpResponse`, …).
- **`TrueAsync\ClickHouse\*`** — the async [ClickHouse client](https://github.com/true-async/php-clickhouse).

## Install
You can add this package to your project using [Composer](https://getcomposer.org/):
```bash
composer require --dev true-async/ide-helper
```
