<?php

use Illuminate\Database\Seeder;
use App\Models\MasterRekomendasi;

class RekomendasiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MasterRekomendasi::create(["code"=>"010", "rekomendasi"=>"Rekomendasi bersifat finansial", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"020", "rekomendasi"=>"Rekomendasi bersifat dapat dinilai dengan uang", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"030", "rekomendasi"=>"Rekomendasi bersifat hukuman", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"040", "rekomendasi"=>"Rekomendasi bersifat keputusan arbitrase", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"050", "rekomendasi"=>"Rekomendasi bersifat penegakan aturan", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"060", "rekomendasi"=>"Rekomendasi bersifat peningkatan efisiensi/produktifitas", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"070", "rekomendasi"=>"Rekomendasi bersifat finansial", "desc"=>null, "flag"=>1]);
        MasterRekomendasi::create(["code"=>"080", "rekomendasi"=>"Rekomendasi bersifat peningkatan efektifitas", "desc"=>null, "flag"=>1]);
    }
}
