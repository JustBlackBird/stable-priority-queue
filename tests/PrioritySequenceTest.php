<?php

declare(strict_types=1);

namespace JustBlackBird\StablePriorityQueue\Tests;

use JustBlackBird\StablePriorityQueue\PrioritySequence;
use PHPUnit\Framework\TestCase;

class PrioritySequenceTest extends TestCase
{
    private PrioritySequence $sequence;

    protected function setUp(): void
    {
        $this->sequence = new PrioritySequence();
    }

    public function testItemsAreSortedByPriority(): void
    {
        $last = $this->sequence->next(0);
        $first = $this->sequence->next(10);

        $this->assertGreaterThan(0, strcmp($first, $last));
    }

    public function testItemsWithNegativePriority(): void
    {
        $last = $this->sequence->next(-10);
        $first = $this->sequence->next(10);

        $this->assertGreaterThan(0, strcmp($first, $last));
    }

    public function testItemsAreSortedByGenerationOrder(): void
    {
        $first = $this->sequence->next(0);
        $last = $this->sequence->next(0);

        $this->assertGreaterThan(0, strcmp($first, $last));
    }
}
