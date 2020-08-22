<?php

declare(strict_types=1);

namespace JustBlackBird\StablePriorityQueue\Tests;

use JustBlackBird\StablePriorityQueue\Queue;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    private Queue $queue;

    protected function setUp(): void
    {
        $this->queue = new Queue();
    }

    public function testValuesWithDifferentPriorities(): void
    {
        $this->queue->insert('first', 100);
        $this->queue->insert('second', 50);
        $this->queue->insert('third', 0);

        $this->assertSame('first', $this->queue->extract());
        $this->assertSame('second', $this->queue->extract());
        $this->assertSame('third', $this->queue->extract());
    }

    public function testValuesWithSamePriorities(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $this->queue->insert($i, 0);
        }

        $current = -1;
        while (!$this->queue->isEmpty()) {
            $next = $this->queue->extract();
            $this->assertGreaterThan($current, $next);
            $current = $next;
        }
    }

    public function testIsEmptyForEmptyQueue(): void
    {
        $this->assertTrue($this->queue->isEmpty());
    }

    public function testIsEmptyForNonEmptyQueue(): void
    {
        $this->queue->insert('test', 0);

        $this->assertFalse($this->queue->isEmpty());
    }
}
