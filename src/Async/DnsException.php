<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception for DNS-related errors: getaddrinfo and getnameinfo.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class DnsException extends \Exception {}
