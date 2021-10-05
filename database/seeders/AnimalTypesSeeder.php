<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Type;
use Illuminate\Database\Seeder;
use Schema;

class AnimalTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $pigBreed = [
            "Pig Breed","Red Wattle","Mukota","Mora Romagnola","Poland China", "Large White", "Ossabaw Island", "Spotted", "Lacombe",
                "Thuoc Nhieu", "Norwegian Landrace","French Landrace","Swallow Belied Mangalitza","Cantonese Pig","Mulefoot","Krskopolje", "Minzhu", "Mangalitza",
                "Gloucestershire Old Spots","British Landrace","Bantu","Philippine Native","Chester White","Welsh","Wuzhishan","Lithuanian Native","Saddleback",
                "German Landrace","Tamworth","Belgian Landrace","Iberian","Hereford","Beijing Black","Large Black","Fengjing","Guinea Hog","Mong Cai","Meishan",
                "Jinhua","American Landrace","Hezuo","Duroc","Danish Landrace","Turopolje","Neijiang","Oxford Sandy and Black","Swedish Landrace","Bulgarian White",
                "Middle White", "Italian Landrace","Belarus Black Pied","Czech Improved White","American Yorkshire","Large Black-white","Ba Xuyen","Choctaw Hog",
                "Hampshire","British Lop" ,"Vietnamese Potbelly","Berkshire","Kunekune","Pietrain","Dutch Landrace","Ningxiang","Kele","Tibetan","Arapawa Island",
                "Yorkshire", "Finnish Landrace","Angeln saddleback", "Other"
        ];

        $goatBreed = [
            "Goat Breed","Angora", "Boer","LaMancha","Nubian" ,"Oberhasli" ,"Saanen","Toggenburg","Other"
        ];

        $cattleBreed = [
            "Cattle Breed","Afrikaner Cattle", "Angus Beef/Aberdeen Angus", "Ankole Cattle", "Beefmaster Cattle","Bonsmara Cattle",
                "Boran Cattle","Brahman Cattle", "Braunvieh Cattle", "Charolais Cattle", "Drakensberger Cattle","Hereford Cattle","Limousin Cattle",
                "Nguni Cattle","Santa Gertrudis Cattle","Shorthorn Cattle","Simbra Cattle","Simmentaler Cattle", "Sussex Cattle","Tuli Cattle","Wagyu Beef","Other"
        ];

        $sheepBreed = [
            "Other"
        ];

        Type::query()->truncate();

        $pigType = Type::create(['type' => 'pig']);

        foreach($pigBreed as $breed){
            Breed::create(['type_id' => $pigType->id, 'breed' => $breed]);
        }


        $goatType = Type::create(['type' => 'goat']);

        foreach($goatBreed as $breed){
            Breed::create(['type_id' => $goatType->id, 'breed' => $breed]);
        }

        $cattleType = Type::create(['type' => 'cattle']);

        foreach($cattleBreed as $breed){
            Breed::create(['type_id' => $cattleType->id, 'breed' => $breed]);
        }

        $sheepType = Type::create(['type' => 'sheep']);

        foreach($sheepBreed as $breed){
            Breed::create(['type_id' => $sheepType->id, 'breed' => $breed]);
        }

        Schema::enableForeignKeyConstraints();

    }
}
