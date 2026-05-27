<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\Collection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\Collection\Result;

#[CoversClass(Result::class)]
final class ResultTest extends TestCase
{
    public function testWithItem(): void
    {
        $sut = Result::create()->withItem([]);
        $this->assertSame('item', $sut->getItemType());
    }

    public function testWithCollection(): void
    {
        $sut = Result::create()->withCollection([]);
        $this->assertSame('collection', $sut->getItemType());
    }
}
