<?php


namespace Wzj\AliVod\Facade;

use Illuminate\Support\Facades\Facade;

class AliVod extends Facade
{
    public static function getFacadeAccessor()
    {
       return 'AliVod';
    }

}