<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when operating on a closed or exhausted pool.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class PoolException extends AsyncException {}
