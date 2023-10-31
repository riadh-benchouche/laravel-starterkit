<?php

namespace App\Enums;

use Exception;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Str;

enum Days: int
{
    case SUNDAY = 0;
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;


    public static function getArray(): array
    {
        return [
            [
                'id' => self::SUNDAY->value,
                'label' => self::getDescription(self::SUNDAY),
            ],
            [
                'id' => self::MONDAY->value,
                'label' => self::getDescription(self::MONDAY),
            ],
            [
                'id' => self::TUESDAY->value,
                'label' => self::getDescription(self::TUESDAY),
            ],
            [
                'id' => self::WEDNESDAY->value,
                'label' => self::getDescription(self::WEDNESDAY),
            ],
            [
                'id' => self::THURSDAY->value,
                'label' => self::getDescription(self::THURSDAY),
            ],
            [
                'id' => self::FRIDAY->value,
                'label' => self::getDescription(self::FRIDAY),
            ],
            [
                'id' => self::SATURDAY->value,
                'label' => self::getDescription(self::SATURDAY),
            ],
        ];
    }


    public static function getDescription(self $value): array|string|Translator
    {
        return match ($value) {
            self::SUNDAY => __('Sunday'),
            self::MONDAY => __('Monday'),
            self::TUESDAY => __('Tuesday'),
            self::WEDNESDAY => __('Wednesday'),
            self::THURSDAY => __('Thursday'),
            self::FRIDAY => __('Friday'),
            self::SATURDAY => __('Saturday'),

        };
    }

    /**
     * @throws Exception
     */
    public static function getDiffDay(string $dayName, int $day): int
    {
        if (self::getValue(Str::upper($dayName)) > $day) {
            $nbr = abs(abs(self::getValue(Str::upper($dayName)) - $day) - 7);
        } else if (self::getValue(Str::upper($dayName)) < $day) {
            $nbr = abs(abs(self::getValue(Str::upper($dayName))) - $day);
        } else {
            $nbr = 0;
        }
        return $nbr;
    }

    /**
     * @throws Exception
     */
    public static function getValue(string $value): array|string|Translator
    {
        return match ($value) {
            "SUNDAY" => self::SUNDAY->value,
            "MONDAY" => self::MONDAY->value,
            "TUESDAY" => self::TUESDAY->value,
            "WEDNESDAY" => self::WEDNESDAY->value,
            "THURSDAY" => self::THURSDAY->value,
            "FRIDAY" => self::FRIDAY->value,
            "SATURDAY" => self::SATURDAY->value,
            default => throw new Exception('Unexpected match value'),

        };
    }

    public function description(): string
    {
        return self::getDescription($this);
    }


}
