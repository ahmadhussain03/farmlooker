<?php

namespace App\Console\Commands;

use App\Models\Image;
use App\Models\RentalEquipment;
use App\Models\TradingAnimal;
use Illuminate\Console\Command;

class ImportImagesToImagesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Rental & Trading Images to images table';

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
        TradingAnimal::chunk(100, function($tradingAnimals){
            foreach($tradingAnimals as $tradingAnimal){
                $image = new Image();
                $image->image = $tradingAnimal->image;

                $tradingAnimal->images()->save($image);
            }
        });


        RentalEquipment::chunk(100, function($rentalEquipments){
            foreach($rentalEquipments as $rentalEquipment){
                $image = new Image();
                $image->image = $rentalEquipment->image;

                $rentalEquipment->images()->save($image);
            }
        });

        return 0;
    }
}
