<?php

namespace App\Pattern\Cart;

use PhpUnitConversion\Unit;
use PhpUnitConversion\Unit\Mass;

/**
 *
 */
class ConversionWeight
{
    protected $weight;
    const DEFAULT_MOD = 1000;
    //
    public function __construct($value)
    {
        $this->weight = $value;
    }
    //
    public function devided()
    {
        return (int) $integer = $this->weight / self::DEFAULT_MOD;
    }

    //
    protected function lastComma()
    {
        $comma = $this->weight % self::DEFAULT_MOD;

        return $comma > 280 ? 1 : 0;
    }

    //
    public function result()
    {
        return $this->lastComma() + $this->devided();
    }
}
