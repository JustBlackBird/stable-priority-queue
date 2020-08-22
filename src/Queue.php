<?php

declare(strict_types=1);

namespace JustBlackBird\StablePriorityQueue;

/**
 * Priority queue implementation which keeps stable order for items with
 * equal priority.
 *
 * @template TValue
 */
final class Queue
{
    private PrioritySequence $sequence;
    private \SplPriorityQueue $queue;

    public function __construct()
    {
        $this->sequence = new PrioritySequence();
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * Inserts an item into the queue.
     *
     * @param mixed $value
     * @param int $priority
     *
     * @psalm-param TValue $value
     */
    public function insert($value, int $priority): void
    {
        $this->queue->insert($value, $this->sequence->next($priority));
    }

    /**
     * Extracts an item with maximum priority.
     *
     * @return mixed
     *
     * @psalm-return TValue
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function extract()
    {
        return $this->queue->extract();
    }

    /**
     * Checks if the queue is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->queue->isEmpty();
    }
}
