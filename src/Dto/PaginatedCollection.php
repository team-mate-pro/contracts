<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Dto;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template-covariant T
 * @implements IteratorAggregate<int, T>
 */
final class PaginatedCollection implements IteratorAggregate
{
    /**
     * @var array<T> $items
     */
    private array $items;

    private int $count;
    private readonly Pagination $pagination;

    /**
     * @param int $count - count of all matching results
     * @param array<T> $items
     */
    public function __construct(array $items, int $count, ?Pagination $pagination = null)
    {
        $this->pagination = $pagination ?? Pagination::withNoLimit();
        $this->items = $items;
        $this->count = $count;
    }

    /**
     * @return array<T>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * @return Traversable<T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
