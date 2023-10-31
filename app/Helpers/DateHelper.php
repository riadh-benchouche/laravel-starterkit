<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Return a formatted & localized carbon date
     * if no locale provided it will use system locale
     *
     * @param string $date
     * @param string|null $locale
     * @return string
     */
    public static function carbonLocalizedFormat($date, $locale = null): string
    {
        $appLocale = app()->i18n->getLocale();

        if (($locale === null)) {
            $locale = $appLocale['locale'] ?? 'en_EN.UTF-8';
        }

        setlocale(LC_TIME, $locale);
        Carbon::setLocale(app()->getLocale());

        return ucfirst(Carbon::parse($date)->formatLocalized('%A %d %b %Y'));
    }

    /**
     * @param $date
     * @param null $locale
     * @return string
     */
    public static function carbonLocalizedShortFormat($date, $locale = null): string
    {
        $appLocale = app()->i18n->getLocale();

        if (($locale === null)) {
            $locale = $appLocale['locale'] ?? 'en_EN.UTF-8';
        }

        setlocale(LC_TIME, $locale);
        Carbon::setLocale(app()->getLocale());

        return ucfirst(Carbon::parse($date)->formatLocalized('%B %Y'));
    }

    public static function carbonSimpleFormat($date) {
        return ucfirst(Carbon::parse($date)->format('d/m/Y H:m'));
    }
}
