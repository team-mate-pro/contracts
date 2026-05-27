<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\Collection;

use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use TeamMatePro\Contracts\Collection\PaginatedCollection;
use TeamMatePro\Contracts\Collection\Pagination;
use TeamMatePro\Contracts\Collection\Result;

#[CoversClass(Result::class)]
#[UsesClass(PaginatedCollection::class)]
#[UsesClass(Pagination::class)]
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

    public function testMapWithItem(): void
    {
        $old = Result::create()->withItem([
            'one' => 'one'
        ]);

        $this->assertEquals(['one' => 'one'], $old->getResult());

        $callback = fn(array $i) => array_merge($i, [
            'two' => 'two',
        ]);

        $new = $old->map($callback);

        $this->assertEquals(['one' => 'one'], $old->getResult());
        $this->assertEquals(['one' => 'one', 'two' => 'two'], $new->getResult());
    }

    public function testMapWithCollection(): void
    {
        $old = Result::create()->withCollection([
            ['id' => 1],
            ['id' => 2],
        ]);

        $new = $old->map(
            fn(array $items) => array_map(
                fn(array $item) => ['id' => $item['id'], 'doubled' => $item['id'] * 2],
                $items,
            ),
        );

        $this->assertEquals([['id' => 1], ['id' => 2]], $old->getResult());
        $this->assertEquals(
            [['id' => 1, 'doubled' => 2], ['id' => 2, 'doubled' => 4]],
            $new->getResult(),
        );
        $this->assertSame('collection', $new->getItemType());
    }

    public function testMapPreservesMetaAndErrorCode(): void
    {
        $old = Result::create()
            ->withItem(['n' => 1])
            ->withMeta('count', 1)
            ->withErrorCode('E_NONE');

        $new = $old->map(fn(array $i) => ['n' => $i['n'] * 10]);

        $this->assertSame(['count' => 1], $new->getMeta());
        $this->assertSame('E_NONE', $new->getErrorCode());
        $this->assertEquals(['n' => 10], $new->getResult());
    }

    public function testMapCollectionRejectsNonArrayCallbackReturn(): void
    {
        $sut = Result::create()->withCollection([['id' => 1]]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Mapping a collection requires the callback to return an array');

        $sut->map(fn() => new stdClass());
    }

    public function testGetDataTypeIsNullForEmptyResult(): void
    {
        $this->assertNull(Result::create()->getDataType());
    }

    public function testGetDataTypeIsNullForScalarPayload(): void
    {
        $sut = Result::create()->with('plain string');

        $this->assertNull($sut->getDataType());
    }

    public function testGetDataTypeIsNullForEmptyArray(): void
    {
        $sut = Result::create()->withCollection([]);

        $this->assertNull($sut->getDataType());
    }

    public function testGetDataTypeIsNullForArrayOfScalars(): void
    {
        $sut = Result::create()->withCollection(['a', 'b', 'c']);

        $this->assertNull($sut->getDataType());
    }

    public function testGetDataTypeForSingleObject(): void
    {
        $sut = Result::create()->withItem(new stdClass());

        $this->assertSame(
            ['fqcn' => stdClass::class, 'shortName' => 'stdClass'],
            $sut->getDataType(),
        );
    }

    public function testGetDataTypeForArrayOfObjects(): void
    {
        $sut = Result::create()->withCollection([new stdClass(), new stdClass()]);

        $this->assertSame(
            ['fqcn' => stdClass::class, 'shortName' => 'stdClass'],
            $sut->getDataType(),
        );
    }

    public function testGetDataTypeForPaginatedCollection(): void
    {
        $collection = new PaginatedCollection([new stdClass()], 1, new Pagination(0, 10));
        $sut = Result::create()->with($collection);

        $this->assertSame(
            ['fqcn' => stdClass::class, 'shortName' => 'stdClass'],
            $sut->getDataType(),
        );
    }

    public function testGetDataTypeIsNullForEmptyPaginatedCollection(): void
    {
        $collection = new PaginatedCollection([], 0, new Pagination(0, 10));
        $sut = Result::create()->with($collection);

        $this->assertNull($sut->getDataType());
    }

    public function testGetDataTypeReturnsShortNameForNamespacedClass(): void
    {
        $sut = Result::create()->withItem(new Pagination(0, 10));

        $this->assertSame(
            ['fqcn' => Pagination::class, 'shortName' => 'Pagination'],
            $sut->getDataType(),
        );
    }
}
