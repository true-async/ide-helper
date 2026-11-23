<?php
declare(strict_types=1);

/**
 *  TrueAsync enhanced PDO with built-in connection pooling.
 *
 * @see https://true-async.github.io/en/docs/components/pdo-pool.html
 */
class PDO
{
    /**
     * Share one connection between coroutines through a pool (bool).
     *
     * A transaction left open by a coroutine is rolled back when its connection returns to the pool; a connection
     * whose rollback fails, or that is still in a transaction after it, is closed instead. Cannot be combined with
     * `PDO::ATTR_PERSISTENT`, which throws inside any coroutine other than the main one.
     * Default: false
     */
    public const ATTR_POOL_ENABLED = 22;

    /**
     * Connections opened up front and kept idle (int).
     *
     * The healthcheck does not top the pool back up to this number: a connection it drops is replaced by the next
     * coroutine that asks for one.
     * Default: 0
     */
    public const ATTR_POOL_MIN = 23;

    /**
     * Connections the pool may hold in total, idle and busy (int). A coroutine asking past the limit waits for one
     * to come back.
     * Default: 10
     */
    public const ATTR_POOL_MAX = 24;

    /**
     * How often idle connections are checked, in milliseconds (int, 0 disables).
     * Default: 0
     */
    public const ATTR_POOL_HEALTHCHECK_INTERVAL = 25;

    /**
     * Prepared statements cached per pooled connection (int, 0 disables).
     * Default: 0
     */
    public const ATTR_POOL_STMT_CACHE_SIZE = 26;

    /**
     * SQL run on every pooled connection after it connects and after it is cleaned for reuse (string or list of
     * strings, construction only). Rows a statement returns are discarded. The statements must not open a
     * transaction or change autocommit; one that fails fails the connect.
     */
    public const ATTR_POOL_INIT_SQL = 27;

    /**
     * Returns the TrueAsync connection pool object.
     *
     * Available only if PDO was instantiated with `PDO::ATTR_POOL_ENABLED => true`.
     * Otherwise, returns `null`.
     *
     * The pool is for inspection (statistics, circuit breaker): its factory is internal, so `acquire()`,
     * `tryAcquire()` and `release()` on it throw {@see \Async\PoolException}.
     *
     * @return \Async\Pool|null
     */
    public function getPool(): ?\Async\Pool
    {
        return null;
    }
}