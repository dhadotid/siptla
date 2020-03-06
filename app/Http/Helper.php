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
function adddate($tgl,$add)
{
    $tgl1 = $tgl;// pendefinisian tanggal awal
    $tgl2 = date('Y-m-d', strtotime('+'.$add.' days', strtotime($tgl1)));
    return $tgl2;
}

function jenis_level()
{
    $level=[
            'administrator'=>'Administrator',
            'auditor-junior'=>'Auditor Junior',
            'auditor-senior'=>'Auditor Senior',
            'kepala-spi'=>'Kepala SPI',
            'pic-unit'=>'PIC Unit'
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
function status_lhp()
{
    $status=array('Create oleh Unit Kerja','Belum direview SPI','Sedang direview SPI','Sudah direview SPI','Sudah dipublish oleh SPI');
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
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
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
    $rincian=['Sewa','Uang Muka','Listrik','Piutang','Piutang Karyawan','Hutang Titipan','Penutupan Rekening','Umum'];
    $drinc=array();
    foreach($rincian as $k=>$v)
    {
        $drinc[str_replace('-','',str_slug($v))]=$v;
    }
    return $drinc;
}
?>