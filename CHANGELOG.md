# Changelog

## 0.8.0

Brought the `TrueAsync\*` HTTP-server stubs fully back in sync with the extension — they had drifted to the 0.6.5 surface and were missing everything shipped since. Verified against the live extension by reflection: the file now covers the whole public class / enum / function surface.

New:

- **WebSocket** — the entire surface: `WebSocket`, `WebSocketMessage`, `WebSocketUpgrade`, the `WebSocketCloseCode` enum and the four `WebSocket*Exception` classes. Includes cross-worker pub/sub **topics** (`subscribe()`, `unsubscribe()`, `getTopics()`, `publish()`, `publishBinary()`, `subscriberCount()`), the recv/send/trySend/ping/close API, and `getRemoteAddress()` / `getRemotePort()`.
- **Observability** — `HttpServer::getStats()`, `getRuntimeStats()`, `reload()`, `isHttp2()`, `isHttp3()`; plus the `HttpServerConfig` setters for hot reload (`enableHotReload()`, `enableReloadOnSignal()`), cross-worker stats (`setStatsEnabled()`), multi-sink logging (`setLogSinks()`), WebSocket topics/limits, HTTP/3 pacing and buffers, request scope, TLS buffer and the hq docroot.
- **gRPC / SSE on the request & response** — `HttpRequest::readMessage()` / `getGrpcTimeout()`, `HttpResponse::setGrpcEncoding()` / `writeMessage()` (the SSE `sse*` methods from 0.7.3 are retained).

Also corrected two stale docblocks: `WebSocket::publish()` throws `WebSocketBackpressureException` over the publish rate limit and `subscribe()` throws over the subscription cap; and the `WebSocketUpgrade` three-arg handler is selected by PHP dropping undeclared args, not by Reflection.

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
