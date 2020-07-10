<?php
function selisihhari($tgl1,$tgl2,$weekend=1)
{
    $begin = new DateTime($tgl1);
    $end = new DateTime($tgl2);

    $daterange     = new DatePeriod($begin, new DateInterval('P1D'), $end);
   
    $i=0;
    $x     =    0;
    $end     =    1;

    foreach($daterange as $date){
        $daterange     = $date->format("Y-m-d");
        $datetime     = DateTime::createFromFormat('Y-m-d', $daterange);
        $day         = $datetime->format('D');

        if($weekend==1)
        {

            if($day!="Sun" && $day!="Sat") {
                //echo $i;
                $x    +=    $end-$i;
                
            }
            $end++;
        }
        else
        {
            $x++;
        }
        $i++;
    }    
    return $x;
}
function isInDate($date1,$date2)
{
    $today = date('Y-m-d');
    $dToday=date('Y-m-d', strtotime($today));
    //echo $paymentDate; // echos today! 
    $date_1 = date('Y-m-d', strtotime($date1));
    $date_2 = date('Y-m-d', strtotime($date2));

    if (($dToday >= $date_1) && ($dToday <= $date_2)){
        return true;
    }else{
        return false;  
    }
}
function adddate($tgl,$add)
{
    $tgl1 = $tgl;// pendefinisian tanggal awal
    $tgl2 = date('Y-m-d', strtotime('+'.$add.' days', strtotime($tgl1)));
    return $tgl2;
}
function periodereview()
{
    $data['tanggal_mulai']=4;
    $data['tanggal_selesai']=25;
    return $data;
}
function jenis_level()
{
    $level=[
            'administrator'=>'Administrator',
            'auditor-junior'=>'Auditor Junior',
            'auditor-senior'=>'Auditor Senior',
            'kepala-spi'=>'Kepala SPI',
            'pic-unit'=>'PIC Unit',
            'super-user'=>'Senior Auditor 2',
            'pimpinan-kepala-spi'=>'Pimpinan Kepala SPI',
            'pimpinan-kepala-bidang'=>'Pimpinan Kepala Bidang'
        ];
    return $level;
}

function hitunghari($mulai,$akhir,$jenis)
{

    if($mulai==$akhir)
        $jlh=$mulai;
    else
        $jlh=$akhir-$mulai+1;

    if($jenis=='minggu')
    {
        $jumlah=$jlh*7;
    }
    else if($jenis=='bulan')
    {
        $jumlah=$jlh*30;
    }
    else if($jenis=='tahun')
    {
        $jumlah=$jlh*365;
    }
    else
        $jumlah=$jlh;

    return $jumlah;
}
function rupiah($nominal)
{
    return number_format($nominal,0,',','.');
}
function generateid($str)
{
    return abs(crc32(md5(rand().'-'.sha1($str))));
}
function tgl_indo($date)
{
    $tgl=date('d',strtotime($date));
    $bln=date('n',strtotime($date));
    $thn=date('Y',strtotime($date));

    return $tgl.' '.getbulan($bln).' '.$thn;
}
function getbulan($bln)
{
    $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return $bulan[$bln-1];
}
function getbulantoangka($bln)
{
    $bulan=['Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12];
    return $bulan[$bln];
}
function betweendate($cekdate,$date1,$date2)
{
    $currentDate = $cekdate;
    $currentDate = date('Y-m-d', strtotime($currentDate));
    
    $startDate = date('Y-m-d', strtotime($date1));
    $endDate = date('Y-m-d', strtotime($date2));
    
    if (($currentDate >= $startDate) && ($currentDate <= $endDate)){
       return 1;
    }else{
        return 0;
    }
}
function datauserpic($data)
{
    $user=array();
    foreach($data as $k=>$v)
    {
        $user[$v->id]=$v;
    }
    return $user;
}
function bataswaktu()
{
    $bataswaktu=[
        'sudah-masuk-batas-waktu-penyelesaian' => 'Sudah Masuk Batas Waktu Penyelesaian',
        'melewati-batas-waktu-penyelesaian' => 'Melewati Batas Waktu Penyelesaian',
        'belum-masuk-batas-waktu-penyelesaian' => 'Belum Masuk Batas Waktu Penyelesaian'
    ];
    return $bataswaktu;
}
function status_lhp()
{
    $status=array('Create oleh Unit Kerja','Belum direview SPI','Sedang direview SPI','Sudah direview SPI','Sudah dipublish oleh SPI');
    return $status;
}
function total_data(){
    $status=['Sudah Selesai', 'Belum Selesai'];
    return $status;
}
function status_lhp_key()
{
    $status=array('Create oleh Unit Kerja','Belum direview SPI','Sedang direview SPI','Sudah direview SPI','Sudah dipublish oleh SPI');
    $dstatus=array();
    foreach($status as $k=>$v)
    {
        $dstatus[str_slug($v)]=$v;
    }
    return $dstatus;
}
function generate_color_total_data($i){
    if($i == 1)
        return '#5895f1';
    return '#a8d1f5';
}
function generate_color_status($statusId){
    if($statusId == 1)
        return '#6097d2';
    else if($statusId == 2)
        return '#df7c43';
    else if($statusId == 3)
        return '#9b9b9b';
    else if($statusId == 4)
        return '#eec659';
}
function generate_color_tindak_lanjut($i){
    if($i==0)
        return '#6097d2';
    elseif($i == 1)
        return '#df7c43';
    else if($i == 2)
        return '#eec659';
    else if($i == 3)
        return '#78a74c';
    else if($i == 4)
        return '#9b9b9b';
}
function generate_color_one()
{
   $col=random_color();
   
    return '#'.$col;
}
function generate_color($jlhdata)
{
    $color=array();
    for($x=1;$x<=$jlhdata;$x++)
    {
        $col=random_color();
        $color[]='#'.cekcolor($col);
    }

    return $color;
}

function cekcolor($color)
{
    $col=random_color();
    if($col!=$color)
        return $col;
    else
        cekcolor($color);
}

function random_color_part() {

    return str_pad( dechex( mt_rand( 80, 255 ) ), 2, '0', STR_PAD_LEFT);
    // $str = '';
    // for($i = 0 ; $i < 3 ; $i++) {
    //     $str .= dechex( rand(170 , 255) );
    // }
    // return $str;
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

function toArray($data,$colm)
{
    $res=array();
    foreach($data as $k=>$v)
    {
        $res[$v->{$colm}][]=$v;
    }
    return $res;
}
function rinciantindaklanjut()
{
    // $rincian=['Sewa','Uang Muka','Listrik','Piutang','Piutang Karyawan','Hutang Titipan','Penutupan Rekening','Umum','Kontribusi','Non Setoran','PKS'];
    //yang belum diambil -> Non Setoran, PKS
    //yang belum bisa nambah rincian -> kontribusi
    $rincian=[
            'Setoran - Pengembalian Sisa Uang Muka','Setoran - Piutang Karyawan','Setoran - Biaya Listrik','Setoran - Umum',
            'Setoran - Kontribusi','Setoran - Piutang','Setoran - Pembayaran Sewa','Non Setoran',
            'Non Setoran - Penutupan Rekening','Non Setoran - Pertanggungjawaban Uang Muka', 'Non Setoran - Perjanjian Kerjasama',
            'Non Setoran - Umum' //'Non Setoran - Hutang Titipan'
        ];
    $drinc=array();
    foreach($rincian as $k=>$v)
    {
        //Kenapa ada if disini? karna jika merubah id keseluruhan, maka akan perlu perubahan di banyak if dalamnya. 
        //Sehingga disini dibuat if untuk mempercepat.
        //Karna deadlinenya hanya 2 hari :'(
        if($v == 'Setoran - Pembayaran Sewa'){
            $drinc['sewa']=$v;
        }else if($v == 'Setoran - Biaya Listrik'){
            $drinc['listrik']=$v;
        }else if($v == 'Setoran - Piutang'){
            $drinc['piutang']=$v;
        }else if($v == 'Setoran - Piutang Karyawan'){
            $drinc['piutangkaryawan']=$v;
        // }else if($v == 'Non Setoran - Hutang Titipan'){
        //     $drinc['hutangtitipan']=$v;
        }else if($v == 'Non Setoran - Penutupan Rekening'){
            $drinc['penutupanrekening']=$v;
        }else if($v == 'Setoran - Umum'){
            $drinc['umum']=$v;
        }else if($v == 'Setoran - Kontribusi'){
            $drinc['kontribusi']=$v;
        }else if($v == 'Setoran - Pengembalian Sisa Uang Muka'){
            $drinc['uangmuka']=$v;
        }
        else{
            $drinc[str_replace('-','',str_slug($v))]=$v;
        }
    }
    return $drinc;
}
function singkatanstatus($status)
{
    $s=explode('(',$status);
    $st=str_replace(')','',$s[1]);
    return $st;
}
function warnasingkatanstatus($status)
{
    if($status=='BTL')
        return 'warning';
    elseif($status=='BS')
        return 'info';
    elseif($status=='SS')
        return 'success';
    elseif($status=='TDL')
        return 'danger';
}
?>