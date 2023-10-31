<?php

namespace App\Enums;

use Illuminate\Contracts\Translation\Translator;
use JetBrains\PhpStorm\Pure;

enum Status: int
{
    case ENABLED = 1;
    case DISABLED = 2;
    case DELETED = 3;
    case OPENED = 4;
    case PENDING = 5;
    case CLOSED = 6;
    case APPROVED = 7;
    case REJECTED = 8;
    case CANCELED = 9;
    case EXPIRED = 10;
    case COMPLETED = 11;
    case FAILED = 12;
    case IN_PROGRESS = 13;
    case IN_REVIEW = 14;

    public static function getArray(): array
    {
        return [
            [
                'id' => self::DISABLED->value,
                'label' => self::getDescription(self::DISABLED),
            ],
            [
                'id' => self::ENABLED->value,
                'label' => self::getDescription(self::ENABLED),
            ],
            [
                'id' => self::DELETED->value,
                'label' => self::getDescription(self::DELETED),
            ],
            [
                'id' => self::OPENED->value,
                'label' => self::getDescription(self::OPENED),
            ],
            [
                'id' => self::PENDING->value,
                'label' => self::getDescription(self::PENDING),
            ],
            [
                'id' => self::CLOSED->value,
                'label' => self::getDescription(self::CLOSED),
            ],
            [
                'id' => self::APPROVED->value,
                'label' => self::getDescription(self::APPROVED),
            ],
            [
                'id' => self::REJECTED->value,
                'label' => self::getDescription(self::REJECTED),
            ],
            [
                'id' => self::CANCELED->value,
                'label' => self::getDescription(self::CANCELED),
            ], [
                'id' => self::EXPIRED->value,
                'label' => self::getDescription(self::EXPIRED),
            ],
            [
                'id' => self::COMPLETED->value,
                'label' => self::getDescription(self::COMPLETED),
            ],
            [
                'id' => self::FAILED->value,
                'label' => self::getDescription(self::FAILED),
            ],
            [
                'id' => self::IN_PROGRESS->value,
                'label' => self::getDescription(self::IN_PROGRESS),
            ],
            [
                'id' => self::IN_REVIEW->value,
                'label' => self::getDescription(self::IN_REVIEW),
            ],
        ];
    }

    /**
     * @param Status $value
     * @return array|string|Translator
     */
    public static function getDescription(self $value): array|string|Translator
    {
        return match ($value) {
            self::ENABLED => __('enabled'),
            self::DISABLED => __('disabled'),
            self::DELETED => __('deleted'),
            self::OPENED => __('opened'),
            self::PENDING => __('pending'),
            self::CLOSED => __('closed'),
            self::APPROVED => __('approved'),
            self::REJECTED => __('rejected'),
            self::CANCELED => __('canceled'),
            self::EXPIRED => __('expired'),
            self::COMPLETED => __('completed'),
            self::FAILED => __('failed'),
            self::IN_PROGRESS => __('in progress'),
            self::IN_REVIEW => __('in review')
        };
    }

    public function description(): string
    {
        return self::getDescription($this);
    }

    public static function getBadge(self $value): string
    {
        return match ($value) {
            self::ENABLED => 'badge-success',
            self::DISABLED => 'badge-danger',
            default => 'badge-info'
        };
    }

    #[Pure] public function badge(): string
    {
        return self::getBadge($this);
    }

    public static function getChecked(self $value, $checked = 'checked') {
        return match ($value) {
            self::ENABLED => $checked,
            default => ''
        };
    }

    #[Pure] public function checked() {
        return self::getChecked($this);
    }

}
