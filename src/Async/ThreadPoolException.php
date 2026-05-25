<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown by ThreadPool operations (e.g. submitting to a closed pool).
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class ThreadPoolException extends \Exception {}
