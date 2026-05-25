<?php

declare(strict_types=1);

namespace Async;

/**
 * Why a channel was closed.
 * @since 8.6
 */
enum ChannelCloseReason: string
{
    case EXPLICIT       = 'explicit';
    case DISPOSED       = 'disposed';
    case NO_PRODUCERS   = 'no producers timeout';
    case NO_CONSUMERS   = 'no consumers timeout';
    case DEADLOCK       = 'deadlock';
    case SCOPE_DISPOSED = 'scope disposed';
}