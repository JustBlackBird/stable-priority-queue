<?php

declare(strict_types=1);

namespace JustBlackBird\StablePriorityQueue;

/**
 * Generates sequence with prioritized items.
 *
 * @internal
 */
final class PrioritySequence
{
    private int $timestamp = 0;
    private int $offset = 0;

    /**
     * Generates unique sequence item.
     *
     * Each item is a 128-bit binary string. Generated items follow rules:
     * - for two items generated with different priorities the item with
     *   lower priority will be smaller than the item generated with higher
     *   priority.
     * - for two items generated with equal priorities the one which was
     *   generated earlier is bigger one.
     *
     * In other words the items are sorted in functions like strcmp by
     * priority first and then by order of generation.
     *
     * @param int $priority
     * @return string
     */
    public function next(int $priority): string
    {
        $now = time();
        if ($this->timestamp !== $now) {
            // Offset is unique within each second.
            $this->timestamp = $now;
            $this->offset = 0;
        }

        // Build unique priority key in form of "XXXXXXXXYYYYZZZZ", where:
        // - "XXXXXXXX" is a 64 bit long binary string which build from
        //   priority used by client code.
        // - "YYYY" is a 32 bit long binary string which is built from current
        //   timestamp. This value is used to split value with same priority
        //   came in different moment of time.
        // - "ZZZZ" is a 32 bit long binary string which is built from unique
        //   offset each message got within time bucket.
        //
        // The resulting value is a binary string which can be used as priority
        // for SplPriorityQueue. Segments of the string make the order
        // functions (like strcmp) sort value by client's priority first, then
        // by timestamp of insertion and by unique index in time bucket at
        // last.
        return pack(
            'JNN',
            // Swap negative and positive blocks of integers to make
            // correct order when integers are compared by bites
            // in big-endian order.
            $priority ^ 1 << 63,
            // Invert timestamps to use lower priority for later timestamps.
            PHP_INT_MAX - $this->timestamp,
            // Invert offset to use lower priority for values that was
            // emitted later.
            PHP_INT_MAX - $this->offset++
        );
    }
}
