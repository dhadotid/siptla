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

function datauserpic($data)
{
    $user=array();
    foreach($data as $k=>$v)
    {
        $user[$v->id]=$v;
    }
    return $user;
}
?>