<?php

declare(strict_types=1);

namespace Async;

/**
 * Interface for objects that can provide a Scope instance.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/interfaces.html
 */
interface ScopeProvider
{
    /**
     * Return the Scope, or null if none is available.
     * @return Scope|null
     */
    public function provideScope(): ?Scope;
}
