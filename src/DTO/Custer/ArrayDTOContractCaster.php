<?php

declare(strict_types = 1);

namespace Khazhinov\PhpSupport\DTO\Custer;

use ArrayAccess;
use Khazhinov\PhpSupport\DTO\Contract\HasDTOContract;
use Khazhinov\PhpSupport\DTO\DataTransferObject;
use LogicException;
use Spatie\DataTransferObject\Caster;
use Traversable;

class ArrayDTOContractCaster implements Caster
{
    /**
     * @param  array<string>  $types
     * @param  string  $itemType
     */
    public function __construct(
        private readonly array $types,
        private readonly string $itemType,
    ) {
    }

    /**
     * @param  array<string, mixed>  $value
     * @return array<mixed>|ArrayAccess
     */
    public function cast(mixed $value): array | ArrayAccess
    {
        foreach ($this->types as $type) {
            if ($type === 'array') {
                return $this->mapInto(
                    destination: [],
                    items: $value
                );
            }

            if (is_subclass_of($type, ArrayAccess::class)) {
                return $this->mapInto(
                    destination: new $type(),
                    items: $value
                );
            }
        }

        throw new LogicException(
            "Caster [ArrayDTOContractCaster] may only be used to cast arrays or objects that implement ArrayAccess."
        );
    }

    /**
     * @param  array<mixed>|ArrayAccess  $destination
     * @param  array<string, mixed>  $items
     * @return array<mixed>|ArrayAccess
     */
    private function mapInto(array | ArrayAccess $destination, array $items): array | ArrayAccess
    {
        if ($destination instanceof ArrayAccess && ! is_subclass_of($destination, Traversable::class)) {
            throw new LogicException(
                "Caster [ArrayDTOContractCaster] may only be used to cast ArrayAccess objects that are traversable."
            );
        }

        foreach ($items as $key => $item) {
            $destination[$key] = $this->castItem($item);
        }

        return $destination;
    }

    /**
     * @param  mixed  $data
     * @return mixed|DataTransferObject
     */
    private function castItem(mixed $data): mixed
    {
        if ($data instanceof $this->itemType) {
            return $data;
        }

        if ($data instanceof HasDTOContract) {
            $data_resolve = $data->getDTO();
            if ($data_resolve instanceof $this->itemType) {
                return $data_resolve;
            }
        }

        if (is_array($data)) {
            return new $this->itemType(...$data);
        }

        throw new LogicException(
            "Caster [ArrayDTOContractCaster] each item must be an array or an instance of the specified item type [{$this->itemType}]."
        );
    }
}
