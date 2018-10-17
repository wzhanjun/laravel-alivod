<?php


namespace Wzj\AliVod;


use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use Illuminate\Support\ServiceProvider;

class AliVodServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/aliVod.php' => config_path('aliVod.php'),
        ]);
    }


    public function register()
    {
        $this->app->singleton('AliVod', function($app) {

            $config = $app->make('config');

            $regionId  = $config->get('aliVod.region_id');
            $accessKey = $config->get('aliVod.access_key');
            $secretKey = $config->get('aliVod.access_key_secret');

            $profile = DefaultProfile::getProfile($regionId, $accessKey, $secretKey);

            return new AliVod(new DefaultAcsClient($profile), $config);

        });
    }


    public function provides()
    {
        return  [AliVod::class];
    }

}