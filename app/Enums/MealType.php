<?php

namespace App\Enums;

enum MealType: int
{
    case Breakfast = 1;
    case Lunch = 2;
    case Dinner = 3;

    public function rate(): float
    {
        return match ($this) {
            self::Breakfast => 0.5,
            self::Lunch => 1.0,
            self::Dinner => 1.0,
        };
    }
}
