<?php

namespace App\Domains\Identity\Domain;

final class UserProfileRules
{
    public const GENDER_MALE = 'male';

    public const GENDER_FEMALE = 'female';

    public const GENDER_OTHER = 'other';

    public const MAX_AVATAR_KILOBYTES = 2048;

    /**
     * @return list<string>
     */
    public static function allowedGenders(): array
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            self::GENDER_OTHER,
        ];
    }

    public static function isValidGender(?string $gender): bool
    {
        if ($gender === null) {
            return true;
        }

        return in_array($gender, self::allowedGenders(), true);
    }

    public static function isValidBirthYear(?int $year, int $currentYear): bool
    {
        if ($year === null) {
            return true;
        }

        return $year >= 1900 && $year <= $currentYear;
    }
}
