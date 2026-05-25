<?php

declare(strict_types=1);

namespace Async;

/**
 * Wraps an exception that originated in a child thread.
 * The original exception is accessible via getRemoteException().
 *
 * @since 8.6
 */
class RemoteException extends AsyncException
{
    private ?\Throwable $remoteException = null;
    private string $remoteClass = '';

    /** Get the original exception from the child thread. */
    public function getRemoteException(): ?\Throwable {}

    /** Get the class name of the original exception in the child thread. */
    public function getRemoteClass(): string {}
}
