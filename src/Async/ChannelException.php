<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when operating on a closed channel.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class ChannelException extends AsyncException {
    public ChannelCloseReason $reason;
}
