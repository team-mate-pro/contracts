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

    /**
     * @var array<string, mixed>
     */
    private array $meta = [];

    private ?string $errorCode = null;

    private function __construct(
        private readonly ResultType $type,
        private readonly ?string    $message = null
    )
    {
    }

    /**
     * @return self<T>
     */
    public static function create(ResultType $type = ResultType::SUCCESS, ?string $message = null): self
    {
        return new self($type, $message);
    }

    /**
     * @param T $item
     * @return self<T>
     */
    public function with($item): self
    {
        $this->data = $item;
        return $this;
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
}
