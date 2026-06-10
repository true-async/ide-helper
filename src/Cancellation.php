<?php

declare(strict_types=1);

/**
 * TrueAsync extension stubs for PhpStorm.
 *
 * Provides IDE-level type information for the `async` PHP extension.
 *
 * @since      8.6
 * @version    0.7.1
 * @link       https://github.com/true-async/php-async
 */
namespace {

    /**
     * Base class for cancellation throwables.
     *
     * A third Throwable root alongside Exception and Error: a Cancellation is
     * NOT an Exception. {@see \Async\AsyncCancellation} extends this class, so
     * `catch (\Exception)` does not catch coroutine cancellations.
     *
     * @see https://true-async.github.io/en/docs/components/exceptions.html
     */
    class Cancellation implements \Throwable
    {
        protected $message = "";
        protected $code = 0;
        protected string $file = "";
        protected int $line = 0;

        public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {}

        final public function getMessage(): string {}

        final public function getCode() {}

        final public function getFile(): string {}

        final public function getLine(): int {}

        final public function getTrace(): array {}

        final public function getTraceAsString(): string {}

        final public function getPrevious(): ?\Throwable {}

        public function __toString(): string {}
    }
}
