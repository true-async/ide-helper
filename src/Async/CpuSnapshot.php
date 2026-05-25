<?php

declare(strict_types=1);

/**
 * Immutable point-in-time snapshot of process and system CPU counters.
 *
 * All time-valued fields are monotonically growing nanosecond counters with an
 * implementation-defined origin. Single values are not directly meaningful —
 * compute deltas between two snapshots taken at different moments to derive
 * CPU usage. See {@see cpu_usage()} for a ready-made delta helper.
 *
 * Cross-platform: identical fields and semantics on Linux and Windows.
 *
 *  - wallNs          monotonic wall-clock time at the moment of capture.
 *  - processUserNs   total user-mode CPU time consumed by all threads of this process.
 *  - processSystemNs total kernel-mode CPU time consumed by all threads of this process.
 *  - systemIdleNs    total idle time across all logical CPUs of the host.
 *  - systemBusyNs    total non-idle time across all logical CPUs of the host
 *                    (user + system + nice + irq + softirq + steal).
 *  - cpuCount        number of logical CPUs visible to the OS at capture time.
 *
 * Note: inside containers `systemIdleNs` / `systemBusyNs` reflect the host, not
 * the cgroup. For per-process backpressure prefer the `process*` fields, which
 * automatically account for affinity and cgroup CPU throttling.
 *
 * @strict-properties
 * @not-serializable
 */
final class CpuSnapshot
{
    public readonly int $wallNs;
    public readonly int $processUserNs;
    public readonly int $processSystemNs;
    public readonly int $systemIdleNs;
    public readonly int $systemBusyNs;
    public readonly int $cpuCount;

    private function __construct() {}

    /**
     * Capture a fresh CPU snapshot.
     */
    public static function now(): CpuSnapshot {}
}