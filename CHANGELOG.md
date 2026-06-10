# Changelog

## 0.7.1

Synced `Async\*` stubs with php-src `ext/async`:

- `Cancellation` is now a **class** (`implements Throwable`), not an interface, and `Async\AsyncCancellation` extends it directly — matching the engine, where a cancellation is a third throwable root and is **not** an `Exception`.
- `Async\CpuSnapshot` moved into the `Async` namespace (was declared in the global namespace).
- `TaskGroup` and `TaskSet` constructors gained the `int $queueLimit` parameter.
- Added the `Async\runtime_stats()` function.

New stub sets:

- **`TrueAsync\*`** — the TrueAsync HTTP server extension.
- **`TrueAsync\ClickHouse\*`** — the async ClickHouse client.
