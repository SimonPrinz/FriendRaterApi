<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class RatingType extends AbstractEnumType
{
    public const UP = 'U';
    public const DOWN = 'D';
    public const LIKE = 'L';

    protected static $choices = [
        self::UP,
        self::DOWN,
        self::LIKE,
    ];
}
