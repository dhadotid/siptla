<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Laporan Temuan Unit Kerja</title>
</head>
<body style="padding:0px; margin:0px;">
    <div class="row" style="padding:0px; margin:0px;">
            <div class="col-md-12 text-center" style="text-align:center">
                <h5>
                    LAPORAN STATUS PENYELESAIAN REKOMENDASI - BIDANG<br>
                    PERIODE <span style="font-weight: bold;text-decoration:underline" id="span_tgl_awal">{{tgl_indo($tgl_awal)}}</span> s.d. <span style="font-weight: bold;text-decoration:underline" id="span_tgl_akhir">{{tgl_indo($tgl_akhir)}}</span>
                    PEMERIKSA <span style="font-weight: bold;text-decoration:underline" id="span_pemeriksa">{{strtoupper($npemeriksa ? $npemeriksa->pemeriksa : '')}}</span><br>
                </h5>
            </div>
        </div>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" border="1">
            <thead>
                <tr class="primary">
                    <th class="text-center" style="width:15px;">#</th>
                    <th class="text-center">Nomor LHP</th>
                    <th class="text-center">Pemeriksa</th>
                    @foreach ($statusrekom as $item)
                        <th class="text-center">{{$item->rekomendasi}}</th>
                    @endforeach
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Selesai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no=1;
                @endphp
                @foreach ($lhp as $k=> $item)
                    
                     <tr>
                        <td class="text-center">{{$no}}</td>
                        <td class="text-left">{{$item->no_lhp}}</td>
                        <td class="text-center">{{$item->code}}</td>
                        @php
                            $jlh=$selesai=0;
                        @endphp
                        @foreach ($statusrekom as $itm)
                            @if (isset($jlh_by_status[$k][$itm->id]))
                                <td class="text-center">{{count($jlh_by_status[$k][$itm->id])}}</td>
                                @php
                                    if($itm->id==1)
                                        $selesai=count($jlh_by_status[$k][$itm->id]);

                                    $jlh+=count($jlh_by_status[$k][$itm->id]);
                                @endphp
                            @else   
                                <td class="text-center">0</td>
                            @endif
                        @endforeach
                        <td class="text-center">{{rupiah($jlh)}}</td>

                        @php
                            if($selesai!=0)
                            {
                                $persen = ($selesai / $jlh * 100);
                            }
                            else
                                $persen=0;
                        @endphp

                        <td class="text-center">{{number_format($persen,2,',','.')}} %</td>
                    </tr> 
                
                    @php
                        $no++;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <style>
            th,td
            {
                font-size:11px;
                padding:2px;
            }
            .text-right{
                text-align:right;
            }
            .text-center{
                text-align:center;
            }
            .text-left{
                text-align:left;
            }
        </style>
</body>