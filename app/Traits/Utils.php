<?php
namespace App\Traits;


trait Utils {



    function convertToDecimal ($fraction)
    {
        $numbers=explode("/",$fraction);
        return round($numbers[0]/$numbers[1],6);
    }
}