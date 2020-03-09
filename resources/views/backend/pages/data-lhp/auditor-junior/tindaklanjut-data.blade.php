 <div class="table-responsive">
                        <div class="row" style="margin-bottom:20px;">
                            <div class="col-md-8">&nbsp;</div>
                            <div class="col-md-4 text-right">
                                <a class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Cetak Data</a>
                                <a class="btn btn-xs btn-success"><i class="fa fa-file-excel-o"></i> Export Ke Excel</a>
                            </div>
                        </div>

                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr class="primary">
                                    <th class="text-center" style="width:15px;">#</th>
                                    <th class="text-center"><div style="width:420px;">Temuan / Rekomendasi</div></th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Tindak<br>Lanjut</th>
                                    <th class="text-center">Rincian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                    $tem=$tgl=$aksi=$pic2=$tindak_lanjut='';
                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                @endphp
                                @foreach ($temuan as $idtemuan=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian=$rinc='';
                                        $tem='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Temuan</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';
                                        if(strlen($item->temuan)>=150)
                                        {
                                            $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b><a href="#" data-toggle="tooltip" title="'.$item->temuan.'">'.substr($item->temuan,0,150).' ...</a></b>
                                            </div>';
                                        }
                                        else
                                        {
                                            $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b>'.$item->temuan.'</b>
                                            </div>';
                                        }
                                        $tem.='</div>';
                                        $tgl='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $pic2='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $aksi='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $tindak_lanjut='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        $tindak_lanjut.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tindak_lanjut.='<div class="col-md-2">&nbsp;</div>';
                                        $tindak_lanjut.='<div class="col-md-10">&nbsp;</div>';
                                        $tindak_lanjut.='</div>';
                                        
                                        $rinc='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        $rinc.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $rinc.='<div class="col-md-12">&nbsp;</div>';
                                        $rinc.='</div>';
                                        
                                        $tem.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No.Rekom</i></small></div>';
                                        $tem.='<div class="col-md-10"><small><i>Rekomendasi</i></small></div>';
                                        $tem.='</div>';

                                        $tgl.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tgl.='<div class="col-md-2">&nbsp;</div>';
                                        $tgl.='<div class="col-md-10">&nbsp;</div>';
                                        $tgl.='</div>';

                                        $pic2.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $pic2.='<div class="col-md-2">&nbsp;</div>';
                                        $pic2.='<div class="col-md-10">&nbsp;</div>';
                                        $pic2.='</div>';
                                        
                                        
                                        $aksi.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $aksi.='<div class="col-md-2">&nbsp;</div>';
                                        $aksi.='<div class="col-md-10">&nbsp;</div>';
                                        $aksi.='</div>';
                                        if(isset($rekomendasi[$item->id_temuan]))
                                        {
                                            foreach($rekomendasi[$item->id_temuan] as $k=>$v)
                                            {
                                                $drekom=strlen($v->rekomendasi);
                                                if($drekom>=250)
                                                    $text_rekom='<a href="#" data-toggle="tooltip" data-placement="top" title="'.$v->rekomendasi.'">'.substr($v->rekomendasi,0,250).'...</a>';
                                                else
                                                    $text_rekom=$v->rekomendasi;

                                                $tem.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';
                                                $tem.='<div class="col-md-11">'.$text_rekom.'</div>';
                                                $tem.='</div>';
                                                
                                                
                                                    if(isset($gettindaklanjut[$v->id]))
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-inverse">'.count($gettindaklanjut[$v->id]).'</span></div>';
                                                        $tindak_lanjut.='</div>';
                                                    }
                                                    else
                                                    {
                                                        $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                        $tindak_lanjut.='</div>';
                                                    }  
                                                

                                                $jlhrincian=0;
                                                if($v->rincian!='')
                                                {
                                                    if(isset($rincian[$v->rincian][$v->id_rekomendasi]))
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-info">'.count($rincian[$v->rincian][$v->id_rekomendasi]).'</i></div>';
                                                        $rinc.='</div>';
                                                        $jlhrincian=count($rincian[$v->rincian][$v->id_rekomendasi]);
                                                    }
                                                    else
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-inverse">0</i></div>';
                                                        $rinc.='</div>';
                                                    }
                                                }
                                                else
                                                {
                                                    $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $rinc.='<div class="col-md-12 text-center" style=""><span class="label label-success">Tidak Ada</i></div>';
                                                    $rinc.='</div>';
                                                }

                                                if($v->pic_2_temuan_id!='')
                                                {
                                                    $listpic2=explode(',', $v->pic_2_temuan_id);
                                                    // print_r($listpic2);
                                                   
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:280px;"><div class="col-md-12 text-left">';
                                                        $c=1;
                                                        $t_pic='';
                                                        $c=0;
                                                        foreach($listpic2 as $kp=>$vp)
                                                        {
                                                            
                                                            if($c<3)
                                                                $pic2.=(isset($pic[$vp]) ? $pic[$vp]->nama_pic : '').'<br>';
                                                            else
                                                                $t_pic.=(isset($pic[$vp]) ? $pic[$vp]->nama_pic : '').'<br>';

                                                            
                                                            $c++;
                                                        }
                                                        
                                                    if($c>3)
                                                        $pic2.='<a href="#" class="label label-default" data-toggle="tooltip" data-html="true" title="'.$t_pic.'">Lainnya</a></div></div>';
                                                    else
                                                        $pic2.='</div></div>';
                                                }
                                                else
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:280px;"><div class="col-md-12 text-left">-</div></div>';

                                                $tgl.='<div style="height:80px;"  id="tgl_penyelesaian_'.$item->id_temuan.'_'.$v->id.'">';
                                                if($v->tanggal_penyelesaian!='')
                                                {
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><span class="label label-info"><i class="fa fa-calendar"></i> '.tgl_indo($v->tanggal_penyelesaian).'</span></div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                   $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><i class="label label-danger">Belum Di Set</i></div>
                                                        </div>';
                                                }
                                                $tgl.='</div>';
                                                    
                                                    $styleaksi='display:none';
                                                    
                                                    if($v->publish_pic_1==1)
                                                    {
                                                        if($v->published=='1')
                                                        {
                                                            $icon='fa-check';
                                                            $color='success';
                                                            $toggle='data-toggle="tooltip" title="Data Rekomendasi Sudah Publish Ke Auditor Senior"';
                                                        }
                                                        else
                                                        {
                                                            $icon='fa-bars';
                                                            $color='primary';
                                                            $toggle='';
                                                        }

                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button '.$toggle.' type="button" class="btn btn-'.$color.' btn-xs" style="height:28px;"><i class="fa '.$icon.'"></i></button>
                                                            <button type="button" class="btn btn-'.$color.' btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';
                                                            
                                                        if($v->published=='1')
                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.',1)" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                        else
                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                            if($v->review_spi=='')
                                                            {
                                                                $aksi.='<li><a disabled data-toggle="tooltip" title="Anda Belum Menambahkan Review/Catatan Monev Untuk Rekomendasi Ini" href="#" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Senior</a></li>';
                                                            }
                                                            else
                                                            {
                                                                if($v->published=='1')
                                                                {
                                                                    $aksi.='<li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-ok"></i> &nbsp;&nbsp;Sudah Publish Ke Senior</a></li>';
                                                                }
                                                                else
                                                                {
                                                                    $aksi.='<li><a href="javascript:publishkesenior('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Senior</a></li>';
                                                                }
                                                            }    

                                                        $aksi.='</ul>
                                                        </div></div>';
                                                    }
                                                    else
                                                    {
                                                        $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                
                                                                <li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                        $aksi.='</ul>
                                                            </div>
                                                        </div>';
                                                    }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left">{!!$tem!!}</td>
                                        <td class="text-center">{!!$tgl!!}</td>
                                        <td class="text-left">{!!$pic2!!}</td>
                                        
                                        <td class="text-center">{!!$tindak_lanjut!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$rinc!!}</td>
                                        <td class="text-center">{!!$aksi!!}</td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
<script>
    $('#table').DataTable();
</script>