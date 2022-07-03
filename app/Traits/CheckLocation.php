<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Config;

trait CheckLocation {

    // static $validDistance = 2000;
    // static $base_latitude = 31.213575888245597;
    // static $base_longitude = 29.955596923828125;

    public function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'meters')
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch ($unit) {
            case 'miles':
                break;
            case 'meters':
                $distance = $distance * 1.609344 * 1000;
        }
        return (round($distance, 9));
    }


    private function getDistanceBetweenPoints2 ($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);
    
      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;
    
      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
 
        

        return round($angle * $earthRadius, 9);
    }

    public function checkDistance($latitude, $longitude)
    {
        $distance = $this->getDistanceBetweenPoints2(Config::first()->latitude, Config::first()->longitude, $latitude, $longitude);
        return ['onPremises' => $distance <= Config::first()->distance, 'distance' => $distance];
    }
    

}