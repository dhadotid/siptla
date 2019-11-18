<?php

use Illuminate\Database\Seeder;

use App\Models\MasterTemuan;

class KelompokTemuanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MasterTemuan::create(['code'=>'01', 'temuan'=>'Kasus yang merugikan Negara', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'02', 'temuan'=>'Kewajiban penyetoran kepada Negara', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'03', 'temuan'=>'Pelanggaran terhadap peraturan perundang-undangan yang berlaku', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'04', 'temuan'=>'Pelanggaran terhadap prosedur dan tatakerja yang telah ditetapkan', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'05', 'temuan'=>'Penyimpangan dari ketentuan pelaksanaan anggaran', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'06', 'temuan'=>'Hambatan terhadap kelancaran proyek', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'07', 'temuan'=>'Hambatan terhadap kelancaran tugas proyek', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'08', 'temuan'=>'Kelemahan administrasi', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'09', 'temuan'=>'Ketidaklancaran pelayanan kepada masyarakat', 'desc'=>null, 'flag'=>1]);
        MasterTemuan::create(['code'=>'10', 'temuan'=>'Temuan pemeriksaan diprogram lainnya', 'desc'=>null, 'flag'=>1]);
    }
}
