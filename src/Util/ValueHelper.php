<?php


namespace App\Util;


/**
 * Class ValueHelper
 * @package App\Util
 */
class ValueHelper
{

    /**
     * @param string $val
     * @return float
     */
    public static function moneyToFloat(string $val): float
    {
        $val = str_replace(".", "", $val);
        $val = str_replace(",", ".", $val);
        return floatval($val);
    }

    /**
     * @param $val
     * @return string
     */
    public static function intToMoney($val): string
    {
        return number_format($val, 2, ',', '.');
    }

    /**
     * @param $val
     * @return bool
     */
    public static function toBinary($val)
    {
        if ($val) return true;
        return false;
    }

    /**
     * @param $val
     * @return string|null
     */
    public static function maskCep($val)
    {
        if (strlen($val) == 8) {
            return substr($val, 0, 5) . "-" . substr($val, 5, 3);
        }
        return null;
    }
}