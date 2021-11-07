<?php

namespace App\Service;

class Verification
{

    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    public static function isValidDate($date, $format = "m/d/Y"): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Format de date : m/d/Y
     * @param $date1
     * @return bool
     */
    public static function isMajor($dateStr): bool
    {
        if(Verification::isValidDate($dateStr)){
            $d = new \DateTime($dateStr);
            $today = new \DateTime('now');
            $diff = $today->diff($d);
            $age = $diff->y;
            return $age >= 18;
        }
        return false;
    }

    /**
     * @param $inch
     * @param $cm
     * @return bool
     */
    public static function isValidSize($inch, $cm): bool
    {
        $inches = $cm/2.54;
        $feet = intval($inches/12);
        $inches = $inches%12;
        return sprintf("%d"."' "."%d".'"', $feet, $inches) === $inch;
    }

}