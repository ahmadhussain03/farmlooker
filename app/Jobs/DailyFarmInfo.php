<?php

namespace App\Jobs;

use App\Models\Farm;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class DailyFarmInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Farm::chunk(50, function($farms){
            foreach($farms as $farm){
                $location = json_decode($farm->centroid);
                if(is_array($location)){
                    $long = $location[0];
                    $lat = $location[1];

                    $result = Http::get("https://api.openweathermap.org/data/2.5/onecall?lat={$lat}&lon={$long}&exclude=hourly,current,minutely,alerts&appid=" . config('services.open_weather.key'));
                    $result = $result->json();
                    if(isset($result['daily'][0])){
                        $detail = $result['daily'][0];
                        $farm->farmInfos()->create([
                            't_max' => $detail['temp']['max'],
                            't_min' => $detail['temp']['min'],
                            'wind_speed' => $detail['wind_speed'],
                            'cloud_cover' => $detail['clouds'],
                            'humidity' => $detail['humidity'],
                            'rainfall' => 0,
                            'msavi' => 0,
                            'ndre' => 0,
                            'recl' => 0,
                            'ndvi' => 0,
                        ]);
                    }
                }

                sleep(1);
            }
        });
    }
}
