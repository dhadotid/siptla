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
?>