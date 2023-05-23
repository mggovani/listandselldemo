<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Weather as WeatherData;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update weather data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cities = ['Delhi', 'Kolkata', 'Ahmedabad', 'Mumbai', 'Indore', 'Gandhinagar', 'Surat', 'Vadodara', 'Morbi', 'Bhavnagar'];

        foreach ($cities as $city) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.weatherstack.com/current?access_key=dbedee80b221219fce09992491330325&query='.$city);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($result);

            $weather = new WeatherData();
            $weather->location = $result->location->name;
            $weather->temperature = $result->current->temperature;
            $weather->date_time = $result->location->localtime;
            $weather->save();
        }

        return true;
    }
}
