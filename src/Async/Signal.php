<?php

declare(strict_types=1);

namespace Async;

/**
 * OS signals for use with {@see signal()}.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/signal.html
 */
enum Signal: int
{
    case SIGHUP   = 1;
    case SIGINT   = 2;
    case SIGQUIT  = 3;
    case SIGILL   = 4;
    case SIGABRT  = 6;
    case SIGFPE   = 8;
    case SIGKILL  = 9;
    case SIGUSR1  = 10;
    case SIGSEGV  = 11;
    case SIGUSR2  = 12;
    case SIGTERM  = 15;
    case SIGBREAK = 21;
    case SIGABRT2 = 22;
    case SIGWINCH = 28;
}
