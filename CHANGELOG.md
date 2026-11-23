# Changelog

## 1.0.0

Stubs checked against php-async 1.0.0, TrueAsync server 1.0.0 and php-clickhouse 0.1.6 by Reflection over both
sides. The server stub is the server's own `ide-stubs/true-async-server.php`: it adds `RateLimit`, `Room`,
`RoomDeliveryException` and 31 methods, drops `HttpResponse::send()`, `isClosed()`, `getBodyStream()` and
`setBodyStream()`, and `HttpResponse::write()` streams; the buffered append is `appendBody()`.

`PDO::ATTR_POOL_INIT_SQL` is added. `FileSystemWatcher::__construct()` gains `$debounceMs`, `$maxHoldMs` and
`$extensions`, and `ThreadPool::reload()` is added. `protect()`, `await()`, `await_any_or_fail()` and the `recv()`,
`await()`, `acquire()`, `tryAcquire()` and `getResult()` methods declare `mixed`, as the extension does.

Corrected against the extension: `await_first_success()` returns `[null, $errors]` when every trigger fails rather
than throwing; `timeout()` is a cancellation token that does nothing on its own; `Scope::disposeSafely()` does not
wait and leaves coroutines as zombies; `Scope::isFinished()` is true right after a cancel; `ThreadPool::close()`
runs the queued tasks; a failed bootloader rejects tasks with its own exception; `exit()` in a task closes the
worker; `Pool::release()` throws for a foreign resource and on the pool of `PDO::getPool()`. `graceful_shutdown()`
and `PDO::ATTR_POOL_ENABLED` document `async.shutdown_grace` and the `ATTR_PERSISTENT` restriction.

## 0.8.3

`Async\Context` corrected against the extension. The class was documented as an
immutable store whose `set()` and `unset()` return new instances; both change the
Context in place and return it, and `set()` refuses a key the Context already holds
unless `$replace` is true. `get()` and `has()` were documented as reading the local
values alone, while both read the Contexts of the Scopes above as well, and `get()`
throws `Async\ContextException`, a class this package did not carry and now does.
`current_context()` was described as the current coroutine's context; it returns the
Context of the current Scope, and the coroutine's own comes from `coroutine_context()`.

`Scope::__construct()` now states what a root Scope costs. No parent stands above it,
so lookup from inside never reaches the main Scope and `request_context()` is null
there; disposal cancels coroutines that outlive the Scope until `allowZombies()` (also
missing from this stub, now added) is called. Mirrors true-async/php-async#237, where a
value set on `root_context()` in an HTTP bootloader was unreachable from the request
handler through `current_context()`.

## 0.8.2

`PDO` pool attributes corrected against the live extension.
`ATTR_POOL_HEALTHCHECK_INTERVAL` was documented as seconds; the attribute has
always been read as milliseconds, so an application asking for a check every 30
seconds got one every 30 milliseconds — laravel-spawn's config carried that
wording downstream, and a pool healthcheck firing 33 times a second was measured
on a live worker. The constants also had invented values (10001…10004); they are
22…26. `ATTR_POOL_STMT_CACHE_SIZE` was missing and is now here.

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
