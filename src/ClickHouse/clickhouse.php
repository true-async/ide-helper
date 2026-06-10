<?php

/**
 * TrueAsync ClickHouse client stubs for IDEs.
 *
 * Provides IDE-level type information for the async ClickHouse client
 * extension (namespace TrueAsync\ClickHouse). This file is never loaded at
 * runtime; it exists purely so editors (PhpStorm, Intelephense, …) and static
 * analysers (PHPStan, Psalm) can resolve the classes the extension registers.
 *
 * @since      8.6
 * @version    0.7.1
 * @link       https://github.com/true-async/php-clickhouse
 */

declare(strict_types=1);

namespace TrueAsync\ClickHouse;

/** Wire compression for the native protocol. */
enum Compression: string
{
    case None = 'none';
    case LZ4 = 'lz4';
    case ZSTD = 'zstd';
}

/** Multi-host connection strategy. */
enum OpenStrategy: string
{
    case InOrder = 'in_order';       // failover (default): later hosts only on earlier failure
    case RoundRobin = 'round_robin'; // balance across hosts
    case Random = 'random';          // pick a random host
}

/** Base exception for all client errors. */
class ClickHouseException extends \RuntimeException
{
}

/** Connection / network / IO failure (connect, read, write, EOF, cancel). */
class ConnectionException extends ClickHouseException
{
}

/**
 * Error returned by the ClickHouse server. The server-side error code is
 * available via getCode().
 */
class ServerException extends ClickHouseException
{
}

/** Protocol decode, checksum, or compression error. */
class ProtocolException extends ClickHouseException
{
}

/**
 * Streaming batch insert: append rows, then flush them to the server in blocks.
 * Flushing applies async backpressure: a full write buffer yields the coroutine
 * until the socket drains, so the producer cannot outrun the server. The
 * connection is held for the batch's lifetime and returned to the pool when the
 * Batch is destroyed; unflushed rows are discarded, so call flush() to persist.
 */
final class Batch
{
    /** Buffer one positional row (cell order matches the columns). */
    public function append(array $row): void {}

    /** Send all buffered rows as one insert; a no-op when nothing is buffered. */
    public function flush(): void {}

    /** Number of buffered rows not yet flushed. */
    public function count(): int {}
}

/**
 * Server-reported statistics for a query, taken from the native protocol's
 * progress and profile packets. Final once the Result is fully consumed (and
 * immediate for statements that return no rows, e.g. DDL or INSERT … SELECT).
 */
final class Summary
{
    /** Rows the server read while executing the query. */
    public readonly int $readRows;

    /** Bytes the server read while executing the query. */
    public readonly int $readBytes;

    /** Rows written (e.g. by INSERT … SELECT); the "affected rows". */
    public readonly int $writtenRows;

    /** Bytes written. */
    public readonly int $writtenBytes;

    /** Rows the server expected to read in total. */
    public readonly int $totalRowsToRead;

    /** Rows that would have matched without a LIMIT; null if no LIMIT applied. */
    public readonly ?int $rowsBeforeLimit;

    /** Server-side execution time, in seconds. */
    public readonly float $elapsed;
}

/**
 * The result of Client::query(). A single-pass, forward-only result: pull rows
 * lazily with foreach / fetch(), or buffer them with fetchAll(). Statements that
 * return no rows (DDL, INSERT … SELECT) yield an empty result. Do not mix
 * foreach and fetch() on the same object; they share one stream position.
 *
 * @implements \Iterator<int, array<string, mixed>>
 */
final class Result implements \Iterator
{
    /** The next row (column => value), or null at end of stream. */
    public function fetch(): ?array {}

    /** All remaining rows at once. */
    public function fetchAll(): array {}

    /** The first column of the next row (handy for scalar queries), or null. */
    public function fetchOne(): mixed {}

    /** Rows written by the query (INSERT … SELECT); shortcut for summary()->writtenRows. */
    public function affectedRows(): int {}

    /** Server-reported statistics for the query. */
    public function summary(): Summary {}

    public function current(): mixed {}

    public function key(): int {}

    public function next(): void {}

    public function rewind(): void {}

    public function valid(): bool {}
}

/**
 * ClickHouse client. One Client owns a hidden, per-coroutine connection pool;
 * query/insert acquire and release a physical connection transparently.
 */
final class Client
{
    public function __construct(array $config) {}

    /**
     * Run a SELECT, DDL or INSERT … SELECT and return a Result. Rows are streamed
     * lazily (foreach / fetch()) or buffered (fetchAll()); no-row statements give
     * an empty Result. The statement has executed by the time this returns.
     */
    public function query(string $sql, array $params = [], array $options = []): Result {}

    /** Columnar batch insert (all rows at once). */
    public function insert(string $table, array $columns, array $rows): void {}

    /** Open a streaming batch insert. */
    public function insertBatch(string $table, array $columns): Batch {}

    /** The underlying TrueAsync pool (advanced escape hatch: stats, lifecycle). */
    public function getPool(): \Async\Pool {}
}
