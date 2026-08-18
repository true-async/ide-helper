<?php

declare(strict_types=1);

namespace Async;

/**
 * Key-value store owned by a Scope and shared by every coroutine running in it.
 *
 * {@see find()}, {@see get()} and {@see has()} read this Context first and then the
 * Contexts of the Scopes above it. A Scope receives a Context only when someone asks
 * for one, so scopes without a Context are skipped rather than ending the search; the
 * search ends at a Scope with no parent, which is the main Scope or one created by
 * `new Scope()`. {@see set()} and {@see unset()} modify this Context in place and
 * return it.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/context.html
 */
final class Context
{
    /**
     * Find a value by key in this Context, then in the Contexts of the Scopes above it.
     *
     * Returns null when no level holds the key.
     *
     * @param string|object $key
     */
    public function find(string|object $key): mixed {}

    /**
     * Get a value by key in this Context, then in the Contexts of the Scopes above it.
     *
     * @param string|object $key
     * @throws ContextException If the key is not found at any level.
     */
    public function get(string|object $key): mixed {}

    /**
     * Check if a key exists in this Context or in the Contexts of the Scopes above it.
     *
     * @param string|object $key
     */
    public function has(string|object $key): bool {}

    /**
     * Find a value by key in this Context alone, ignoring the Scopes above it.
     *
     * @param string|object $key
     */
    public function findLocal(string|object $key): mixed {}

    /**
     * Get a value by key in this Context alone.
     *
     * @param string|object $key
     * @throws ContextException If this Context does not hold the key.
     */
    public function getLocal(string|object $key): mixed {}

    /**
     * Check if this Context holds the key itself, ignoring the Scopes above it.
     *
     * @param string|object $key
     */
    public function hasLocal(string|object $key): bool {}

    /**
     * Set a value by key in this Context.
     *
     * A key already held by this Context is an error unless $replace is true; a key
     * inherited from a Scope above does not count as held.
     *
     * @param string|object $key
     * @param mixed         $value
     * @param bool          $replace Allow replacing a key already held by this Context.
     * @return Context This same Context.
     */
    public function set(string|object $key, mixed $value, bool $replace = false): Context {}

    /**
     * Delete a value by key from this Context; keys held by the Scopes above are left alone.
     *
     * @param string|object $key
     * @return Context This same Context.
     */
    public function unset(string|object $key): Context {}
}
