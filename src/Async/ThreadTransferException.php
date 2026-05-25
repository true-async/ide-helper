<?php

declare(strict_types=1);

namespace Async;

/**
 * Thrown when data transfer between threads fails.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class ThreadTransferException extends AsyncException {}
