<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when a mandatory Context key is missing.
 *
 * Raised by {@see Context::get()} and {@see Context::getLocal()}; {@see Context::find()}
 * and {@see Context::findLocal()} answer null for the same key.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class ContextException extends AsyncException {}
