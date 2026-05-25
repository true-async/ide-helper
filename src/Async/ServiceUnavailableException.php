<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when a service is unavailable.
 *
 * Used by the circuit breaker when the circuit is in the INACTIVE state.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class ServiceUnavailableException extends AsyncException {}
