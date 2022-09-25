<?php

declare(strict_types=1);

namespace Khazhinov\PhpSupport\DTO\Validation;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ClassExists implements Validator
{
    /**
     * @param  bool  $nullable
     */
    public function __construct(
        private readonly bool $nullable = false,
    ) {
    }

    public function validate(mixed $value): ValidationResult
    {
        if ($this->nullable && is_null($value)) {
            return ValidationResult::valid();
        }

        if (! $this->nullable && is_null($value)) {
            return ValidationResult::invalid("Value cannot be null.");
        }

        /** @var string $value */
        if (! class_exists($value)) {
            return ValidationResult::invalid("Class $value not available");
        }

        return ValidationResult::valid();
    }
}
