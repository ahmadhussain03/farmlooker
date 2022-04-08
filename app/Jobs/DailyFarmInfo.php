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
        Farm::chunk(5, function($farms){
            foreach($farms as $farm){
                $location = json_decode($farm->centroid);
                if(is_array($location)){
                    $long = $location[0];
                    $lat = $location[1];

                    $farm->geometry = json_decode($farm->geometry);

                    $result = Http::get("https://api.openweathermap.org/data/2.5/onecall?lat={$lat}&lon={$long}&exclude=hourly,current,minutely,alerts&appid=" . config('services.open_weather.key'))->throw()->json();
                    $ndvi = Http::asForm()->post(config('services.node-server.url') . 'ee/ndvi', [
                        'geometry' => json_encode($farm->geometry->geometry->coordinates)
                    ])->throw()->json();
                    $msavi = Http::asForm()->post(config('services.node-server.url') . 'ee/msavi', [
                        'geometry' => json_encode($farm->geometry->geometry->coordinates)
                    ])->throw()->json();
                    $ndre = Http::asForm()->post(config('services.node-server.url') . 'ee/ndre', [
                        'geometry' => json_encode($farm->geometry->geometry->coordinates)
                    ])->throw()->json();
                    $recl = Http::asForm()->post(config('services.node-server.url') . 'ee/recl', [
                        'geometry' => json_encode($farm->geometry->geometry->coordinates)
                    ])->throw()->json();
                    if(isset($result['daily'][0])){
                        $detail = $result['daily'][0];
                        $farm->farmInfos()->create([
                            't_max' => $detail['temp']['max'],
                            't_min' => $detail['temp']['min'],
                            'wind_speed' => $detail['wind_speed'],
                            'cloud_cover' => $detail['clouds'],
                            'humidity' => $detail['humidity'],
                            'rainfall' => $detail['pop'],
                            'msavi' => json_encode($msavi),
                            'ndre' => json_encode($ndre),
                            'recl' => json_encode($recl),
                            'ndvi' => json_encode($ndvi),
                        ]);
                    }
                }
            }
        });
    }
}
