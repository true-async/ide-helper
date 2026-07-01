# Changelog

## 0.7.4

Synced `TrueAsync\*` with the server extension's WebSocket API (v0.9.0):

- Added `HttpServer::addWebSocketHandler()` — full documentation of the two supported handler signatures (with and without `WebSocketUpgrade`).
- Added `WebSocket`, `WebSocketMessage`, `WebSocketUpgrade` and the `WebSocketCloseCode` enum.
- Added the WebSocket exception hierarchy: `WebSocketException`, `WebSocketClosedException`, `WebSocketBackpressureException`, `WebSocketConcurrentReadException`.
- Added the `HttpServerConfig` WebSocket knobs: `setWsMaxMessageSize()`, `setWsMaxFrameSize()`, `setWsPingIntervalMs()`, `setWsPongTimeoutMs()`, `setWsPermessageDeflate()` (and their getters).
- Added `@link` tags pointing to the corresponding page on [true-async.github.io](https://true-async.github.io) to every class and enum in `src/Server/true-async-server.php` (most had none before).

## 0.7.3

Synced `TrueAsync\HttpResponse` with the server extension's Server-Sent Events API:

- Added `sseStart()`, `sseEvent()`, `sseComment()` and `sseRetry()` — the `text/event-stream` primitives for pushing live events to an `EventSource` client.

## 0.7.1

Synced `Async\*` stubs with php-src `ext/async`:

- `Cancellation` is now a **class** (`implements Throwable`), not an interface, and `Async\AsyncCancellation` extends it directly — matching the engine, where a cancellation is a third throwable root and is **not** an `Exception`.
- `Async\CpuSnapshot` moved into the `Async` namespace (was declared in the global namespace).
- `TaskGroup` and `TaskSet` constructors gained the `int $queueLimit` parameter.
- Added the `Async\runtime_stats()` function.

New stub sets:

- **`TrueAsync\*`** — the TrueAsync HTTP server extension.
- **`TrueAsync\ClickHouse\*`** — the async ClickHouse client.
