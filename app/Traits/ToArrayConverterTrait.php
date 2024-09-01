<?php
declare(strict_types=1);

namespace App\Traits;

use ReflectionClass;

trait ToArrayConverterTrait
{
    public static function toArray(): array
    {
        $reflectionClass = new ReflectionClass(self::class);
        $constants = $reflectionClass->getConstants();
        return array_column($constants, 'value');
    }

    public static function fromValue(string $value): ?self
    {
        $reflectionClass = new ReflectionClass(self::class);
        $constants = $reflectionClass->getConstants();

        foreach ($constants as $enum) {
            if ($enum->value === $value) {
                return $enum;
            }
        }

        return null;
    }
}
