# Changelog

## 0.8.1

Docblock fix on `TrueAsync\HttpServerConfig::setBootloader()`. The old wording —
"Only consulted when `setWorkers()` > 1" — read like a footnote rather than a
rule you have to design around: an embedder put its entire per-worker
initialization in the bootloader, ran with one worker, and got silence. The stub
now states outright that in pool mode every worker is a fresh thread the closure
has to set up, while with a single worker the server runs in the calling
process, which the caller has already prepared — so that setup is the caller's
own. Mirrors true-async/server#142.

## 0.8.0

Completed the `TrueAsync\*` HTTP-server sync. 0.7.4 brought over the WebSocket
classes and the per-class `@link` tags; this release covers the rest of the
extension's public surface and verifies the file against the live extension by
reflection, so it now matches the whole public class / enum / function set. The
`@link` tags from 0.7.4 are kept on every class.

New since 0.7.4:

- **WebSocket topics** — cross-worker pub/sub on `WebSocket`: `subscribe()`,
  `unsubscribe()`, `getTopics()`, `publish()`, `publishBinary()`,
  `subscriberCount()`, plus `getRemoteAddress()` / `getRemotePort()`. And the
  `HttpServerConfig` topic knobs: `setWsMaxSubscriptions()`,
  `setWsPublishRateLimit()`.
- **Observability** — `HttpServer::getStats()`, `getRuntimeStats()`, `reload()`,
  `isHttp2()`, `isHttp3()`; plus the `HttpServerConfig` setters for hot reload
  (`enableHotReload()`, `enableReloadOnSignal()`), cross-worker stats
  (`setStatsEnabled()`), multi-sink logging (`setLogSinks()`), HTTP/3 pacing and
  buffers, request scope, TLS buffer and the hq docroot.
- **gRPC** — `HttpRequest::readMessage()` / `getGrpcTimeout()`,
  `HttpResponse::setGrpcEncoding()` / `writeMessage()`.

Also corrected two stale docblocks: `WebSocket::publish()` throws
`WebSocketBackpressureException` over the publish rate limit and `subscribe()`
throws over the subscription cap; and the `WebSocketUpgrade` three-arg handler is
selected by PHP dropping undeclared args, not by Reflection.

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
