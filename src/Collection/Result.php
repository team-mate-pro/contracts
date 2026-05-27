<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Collection;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function is_iterable;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 */
final class Result implements IteratorAggregate
{
    /**
     * @var T $data
     */
    private $data;

    /** @var 'item'|'collection' */
    private string $itemType = 'item';

    /**
     * @var array<string, mixed>
     */
    private array $meta = [];

    private ?string $errorCode = null;

    private function __construct(
        private readonly ResultType $type,
        private readonly ?string $message = null
    ) {
    }

    /**
     * @return self<T>
     */
    public static function create(ResultType $type = ResultType::SUCCESS, ?string $message = null): self
    {
        return new self($type, $message);
    }

    /**
     * @param array<string|int, mixed>|object $item
     * @return self<array<string|int, mixed>|object>
     */
    public function withItem(array|object $item): self
    {
        /** @var self<array<string|int, mixed>|object> $self */
        $self = $this;
        $self->data = $item;
        $self->itemType = 'item';
        return $self;
    }

    /**
     * @param array<int|string, mixed> $collection
     * @return self<array<string|int, mixed>|object>
     */
    public function withCollection(array $collection): self
    {
        /** @var self<array<string|int, mixed>|object> $self */
        $self = $this;
        $self->data = $collection;
        $self->itemType = 'collection';
        return $self;
    }

    /**
     * @return self<T>
     */
    public function withMeta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;
        return $this;
    }

    /**
     * @return self<T>
     */
    public function withErrorCode(int|string $code): self
    {
        $this->errorCode = (string)$code;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getType(): ResultType
    {
        return $this->type;
    }

    /**
     * @return T
     */
    public function getResult()
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function hasContent(): bool
    {
        return isset($this->data);
    }

    /**
     * @return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        if (is_iterable($this->data)) {
            return new ArrayIterator(is_array($this->data) ? $this->data : iterator_to_array($this->data));
        }

        return new ArrayIterator([$this->data]);
    }

    /**
     * @return 'item'|'collection'
     */
    public function getItemType(): string
    {
        return $this->itemType;
    }

    /**
     * Returns the underlying entry type as FQCN + short name, or null if there is none.
     *
     * - Single object payload → that object's class
     * - PaginatedCollection<X> or array of objects → first object's class
     * - Empty array / scalar / null → null
     *
     * @return array{fqcn: class-string, shortName: string}|null
     */
    public function getDataType(): ?array
    {
        if (!isset($this->data)) {
            return null;
        }

        $object = $this->resolveTypeSource($this->data);

        if ($object === null) {
            return null;
        }

        $fqcn = $object::class;
        $pos = strrpos($fqcn, '\\');

        return [
            'fqcn' => $fqcn,
            'shortName' => $pos === false ? $fqcn : substr($fqcn, $pos + 1),
        ];
    }

    private function resolveTypeSource(mixed $data): ?object
    {
        if (is_object($data) && !$data instanceof PaginatedCollection) {
            return $data;
        }

        if ($data instanceof PaginatedCollection) {
            foreach ($data as $item) {
                if (is_object($item)) {
                    return $item;
                }
            }

            return null;
        }

        if (is_array($data)) {
            $first = $data[array_key_first($data) ?? ''] ?? null;

            return is_object($first) ? $first : null;
        }

        return null;
    }

    /**
     * Returns a new Result with `$callback` applied to the data; the original is unchanged.
     *
     * The callback receives the current data and must return either an array or an object
     * (matching what `withItem` / `withCollection` accept).
     *
     * @param callable $callback
     * @return self<array<string|int, mixed>|object>
     */
    public function map(callable $callback): self
    {
        /** @var array<int|string, mixed>|object $mapped */
        $mapped = $callback($this->data);

        $new = self::create($this->type, $this->message);

        foreach ($this->meta as $key => $value) {
            $new = $new->withMeta($key, $value);
        }

        if ($this->errorCode !== null) {
            $new = $new->withErrorCode($this->errorCode);
        }

        if ($this->itemType === 'item') {
            return $new->withItem($mapped);
        }

        if (!is_array($mapped)) {
            throw new \LogicException('Mapping a collection requires the callback to return an array');
        }

        return $new->withCollection($mapped);
    }
}
