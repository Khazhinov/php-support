<?php

declare(strict_types = 1);

namespace Khazhinov\PhpSupport\DTO\Contract;

use Khazhinov\PhpSupport\DTO\DataTransferObject;

interface HasDTOContract
{
    public function getDTO(): DataTransferObject;
}
