<?php

declare(strict_types=1);

namespace Async;

/**
 * Immutable key-value store propagated through a coroutine hierarchy.
 *
 * Each coroutine inherits the context of its parent. Calling {@see set()}
 * or {@see unset()} returns a new Context instance; the original is not
 * modified.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/context.html
 */
final class Context
{
    /**
     * Find a value by key, searching the current context and all ancestors.
     *
     * @param string|object $key
     */
    public function find(string|object $key): mixed {}

    /**
     * Get a value by key from the current context only.
     *
     * @param string|object $key
     */
    public function get(string|object $key): mixed {}

    /**
     * Check if a key exists in the current context or any ancestor.
     *
     * @param string|object $key
     */
    public function has(string|object $key): bool {}

    /**
     * Find a value by key in the local (non-inherited) context only.
     *
     * @param string|object $key
     */
    public function findLocal(string|object $key): mixed {}

    /**
     * Get a value by key from the local context only.
     *
     * @param string|object $key
     */
    public function getLocal(string|object $key): mixed {}

    /**
     * Check if a key exists in the local context only.
     *
     * @param string|object $key
     */
    public function hasLocal(string|object $key): bool {}

    /**
     * Return a new Context with the given key-value pair set.
     *
     * @param string|object $key
     * @param mixed         $value
     * @param bool          $replace Allow replacing an existing key.
     * @return Context A new Context instance.
     */
    public function set(string|object $key, mixed $value, bool $replace = false): Context {}

    /**
     * Return a new Context with the given key removed.
     *
     * @param string|object $key
     * @return Context A new Context instance.
     */
    public function unset(string|object $key): Context {}
}
