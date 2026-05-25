<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when an operation exceeds its time limit.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class TimeoutException extends \Exception {}
