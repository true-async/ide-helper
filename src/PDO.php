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
     * Enable the built-in TrueAsync asynchronous connection pool.
     * Default: false
     */
    public const ATTR_POOL_ENABLED = 10001;

    /**
     * Minimum number of connections the pool keeps open.
     * Default: 0
     */
    public const ATTR_POOL_MIN = 10002;

    /**
     * Maximum number of concurrent connections in the pool.
     * Default: 10
     */
    public const ATTR_POOL_MAX = 10003;

    /**
     * How often to check if a connection is alive (in seconds).
     * Set to 0 to disable background health checks.
     * Default: 0
     */
    public const ATTR_POOL_HEALTHCHECK_INTERVAL = 10004;

    /**
     * Returns the TrueAsync connection pool object.
     *
     * Available only if PDO was instantiated with `PDO::ATTR_POOL_ENABLED => true`.
     * Otherwise, returns `null`.
     *
     * @return \Async\Pool|null
     */
    public function getPool(): ?\Async\Pool
    {
        return null;
    }
}