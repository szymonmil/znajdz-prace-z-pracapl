<?php

namespace Pracapl\ZnajdzPraceZPracapl\Repository;

use Pracapl\ZnajdzPraceZPracapl\Dto\AppearanceSettings;

class AppearanceSettingsRepository
{
    public static function getSettings(): AppearanceSettings
    {
        $appearanceSettings = get_option('znzppl_appearance');

        return new AppearanceSettings(
            $appearanceSettings['titleFontSize'] ?? null,
            $appearanceSettings['titleColor'] ?? null,
            $appearanceSettings['additionalInfoFontSize'] ?? null,
            $appearanceSettings['additionalInfoColor'] ?? null
        );
    }
}