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
        if (strlen($val) <= 8) {
            $val = str_pad($val, 8, '0', STR_PAD_LEFT);
            return substr($val, 0, 5) . "-" . substr($val, 5, 3);
        }
        return null;
    }

    /**
     * @param $val
     * @return string|null
     */
    public static function maskCPF($val): ?string
    {
        $val = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
        if (strlen($val) <= 11) {
            $val = str_pad($val, 11, '0', STR_PAD_LEFT);
            return substr($val, 0, 3) . "." . substr($val, 3, 3) . "." . substr($val, 6, 3) . "-" . substr($val, 9, 2);
        }
        return null;
    }

    /**
     * @param $val
     * @return string|null
     */
    public static function maskCNPJ($val): ?string
    {
        $val = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
        if (strlen($val) <= 14) {
            $val = str_pad($val, 14, '0', STR_PAD_LEFT);
            return substr($val, 0, 2) . "." . substr($val, 2, 3) . "."
                . substr($val, 5, 3) . "/" . substr($val, 8, 4) . "-" . substr($val, 12, 2);
        }
        return null;
    }
}