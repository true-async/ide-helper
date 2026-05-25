<?php

declare(strict_types=1);

namespace Async;

/**
 * General exception for input/output operations.
 *
 * Can be used with sockets, files, pipes, and other I/O descriptors.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class InputOutputException extends \Exception {}
