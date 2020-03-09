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
                                    <th class="text-center">Temuan / Rekomendasi</th>
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
                                    $tem=$tgl=$aksi=$pic2=$tindak_lanjut=$rinc='';
                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                @endphp
                                @foreach ($temuan as $idlhp=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian=$rinc='';
                                        $tem='<div class="row" style="height:80px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-12 text-center"><small><i>No. LHP</i> : </small> <b><u>'.$item->no_lhp.'</u></b></div>
                                            <div class="col-md-2"><small><i>No. Temuan</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';

                                        if(strlen($item->temuan)>=200)
                                        {
                                            $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b><a href="#" data-toggle="tooltip" title="'.$item->temuan.'">'.substr($item->temuan,0,200).' ...</a></b>
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
                                        
                                        $tindak_lanjut.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $tindak_lanjut.='<div class="col-md-2">&nbsp;</div>';
                                        $tindak_lanjut.='<div class="col-md-10">&nbsp;</div>';
                                        $tindak_lanjut.='</div>';

                                        $aksi.='<div class="row" style="height:20px;border-bottom:1px dotted #ddd">';
                                        $aksi.='<div class="col-md-2">&nbsp;</div>';
                                        $aksi.='<div class="col-md-10">&nbsp;</div>';
                                        $aksi.='</div>';
                                        if(isset($rekomendasi[$item->id_temuan]))
                                        {
                                            foreach($rekomendasi[$item->id_temuan] as $k=>$v)
                                            {
                                                
                                                $tem.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';

                                                if(strlen($v->rekomendasi)>=300)
                                                    $tem.='<div class="col-md-11"><a href="#" data-toggle="tooltip" title="'.$v->rekomendasi.'">'.substr($v->rekomendasi,0,300).' ...</a></div>';
                                                else
                                                    $tem.='<div class="col-md-11">'.$v->rekomendasi.'</div>';

                                                $tem.='</div>';
                                                $jlhtl=0;
                                                
                                                if(isset($gettindaklanjut[$v->id]))
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-info fz-sm">'.count($gettindaklanjut[$v->id]).'</span></div>';
                                                    $tindak_lanjut.='</div>';
                                                    $jlhtl=count($gettindaklanjut[$v->id]);
                                                }
                                                else
                                                {
                                                    $tindak_lanjut.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    // $tindak_lanjut.='<div class="col-md-12 text-center"><span class="label label-danger">0</span></div>';
                                                    $tindak_lanjut.='<div class="col-md-12 text-center" id="jlh_tl_'.$item->id_temuan.'_'.$v->id.'">&nbsp;</div>';
                                                    $tindak_lanjut.='</div>';
                                                }  
                                                

                                                //Rincian
                                                $jlhrincian=0;
                                                if($v->rincian!='')
                                                {
                                                    if(isset($rincian[$v->rincian][$v->id_rekomendasi]))
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-info fz-sm">'.count($rincian[$v->rincian][$v->id_rekomendasi]).'</i></div>';
                                                        $rinc.='</div>';
                                                        $jlhrincian=count($rincian[$v->rincian][$v->id_rekomendasi]);
                                                    }
                                                    else
                                                    {
                                                        $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                        $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-inverse fz-sm">0</i></div>';
                                                        $rinc.='</div>';
                                                    }
                                                }
                                                else
                                                {
                                                    $rinc.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                    $rinc.='<div class="col-md-12 text-center" style="margin-top:5px;"><span class="label label-success">Tidak Ada</i></div>';
                                                    $rinc.='</div>';
                                                }
                                                

                                                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                                                {
                                                    $listpic2=explode(',', $v->pic_2_temuan_id);
                                                    // print_r($listpic2);
                                                   
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:250px;"><div class="col-md-12 text-left">';
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
                                                    $pic2.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:250px;"><div class="col-md-12 text-left">-</div></div>';

                                                $tgl.='<div style="height:80px;"  id="tgl_penyelesaian_'.$item->id_temuan.'_'.$v->id.'">';
                                                if($v->tanggal_penyelesaian!='')
                                                {
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">'.tgl_indo($v->tanggal_penyelesaian).'</div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                    $tgl.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">
                                                            <div class="input-group date" id="datetimepicker2" >
                                                                <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id_temuan.'_'.$v->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:90px !important;min-width:90px !important;font-size:11px; "/>
                                                                <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id_temuan.','.$v->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                            </div>    
                                                        </div>    
                                                    </div>';
                                                }
                                                $tgl.='</div>';
                                                    
                                                    $styleaksi='display:none';
                                                    if($v->pic_2_temuan_id==$user_pic->id)
                                                    {
                                                        if($v->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:inline-block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }
                                                    
                                                    if($v->pic_1_temuan_id==$user_pic->id)
                                                    {

                                                        if($v->tanggal_penyelesaian!='')
                                                        {
                                                            $styleaksi='display:inline-block';
                                                        }
                                                        else
                                                        {
                                                            $styleaksi='display:none';
                                                        }
                                                    }

                                                if($item->publish_pic_2==0 && $item->pic_2_temuan_id!='' && $item->pic_2_temuan_id!=',')
                                                {
                                                    if(in_array($user_pic->id,$listpic2))
                                                    {
                                                        $togl='';
                                                    }
                                                    else
                                                    {
                                                        $togl='data-toggle="tooltip" title="PIC 2 Belum Mengisi Tindak Lanjut"';
                                                    }
                                                    $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button '.$togl.' type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';
                                                    
                                                    if(in_array($user_pic->id,$listpic2))
                                                    {
                                                        // print_r($listpic2);
                                                        $aksi.='<li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>';
                                                        $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                        if($jlhrincian!=0)
                                                        {
                                                            $aksi.=' <li><a href="javascript:tambahrincian('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Tambah Rincian Tindak Lanjut</a></li>';
                                                        }
                                                        if($jlhtl!=0)
                                                        {
                                                            $aksi.=' <li><a class="" href="javascript:publishpic2('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke PIC 1</a></li>';
                                                        }
                                                    }
                                                    $aksi.='</ul></div>
                                                        </div>';
                                                }
                                                elseif($item->publish_pic_2==1 && $item->pic_2_temuan_id!='')
                                                {
                                                    $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                            <div data-toggle="tooltip" title="Data Rekomendasi Sudah Publish Ke PIC 1" class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                                <button type="button" class="btn btn-success btn-xs" style="height:28px;"><i class="fa fa-check"></i></button>
                                                                <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">';
                                                    
                                                    if(in_array($user_pic->id,$listpic2))
                                                    {
                                                        // print_r($listpic2);
                                                        
                                                        $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';
                                                        if($jlhrincian!=0)
                                                        {
                                                            $aksi.=' <li><a href="javascript:tambahrincian('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Tambah Rincian Tindak Lanjut</a></li>';
                                                        }
                                                        
                                                        $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke PIC 1</a></li>';
                                                        
                                                    }
                                                    $aksi.='</ul></div>
                                                        </div>';
                                                }
                                                else{
                                                    if($v->publish_pic_1==1)
                                                    {
                                                        $icon='fa-check';
                                                        $color='success';
                                                        $toggle='data-toggle="tooltip" title="Data Rekomendasi Sudah Publish Ke Auditor"';
                                                    }
                                                    else
                                                    {
                                                        $icon='fa-bars';
                                                        $color='primary';
                                                        $toggle='';
                                                    }
                                                    $aksi.='<div class="row" style="height:80px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                        <div  class="btn-group" style="'.$styleaksi.'" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button '.$toggle.' type="button" class="btn btn-'.$color.' btn-xs" style="height:28px;"><i class="fa '.$icon.'"></i></button>
                                                            <button type="button" class="btn btn-'.$color.' btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                               ';
                                                        if($v->pic_1_temuan_id==$user_pic->id)
                                                        {
                                                            
                                                            if($v->publish_pic_2==1 && $v->pic_2_temuan_id!='')
                                                            {
                                                                $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review & Rangkuman Tindak Lanjut</a></li>';
                                                                 if($v->review_monev!='')
                                                                    {
                                                                        $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                    }
                                                            }

                                                            if($v->publish_pic_1==0)
                                                            {
                                                                $aksi.='<li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>';
                                                            }
                                                            
                                                            if($v->pic_2_temuan_id=='' || $v->pic_2_temuan_id==',')
                                                            {
                                                                // $aksi.=' <li><a href="javascript:reviewtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-file"></i> &nbsp;&nbsp;Review & Rangkuman Tindak Lanjut</a></li>';
                                                                

                                                                if($v->publish_pic_1==1)
                                                                {
                                                                    $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke Auditor</a></li>';
                                                                }
                                                                else 
                                                                {
                                                                    // $aksi.='<li>
                                                                    //     <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                    // </li>';
                                                                     
                                                                    $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                    
                                                                }
                                                            }

                                                            $aksi.='<li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                            

                                                            if($v->pic_2_temuan_id!='')
                                                            {
                                                                if($v->publish_pic_1==1)
                                                                {
                                                                    $aksi.=' <li><a href="#" style="font-size:11px;"><i class="glyphicon glyphicon-check"></i> &nbsp;&nbsp;Sudah Publish Ke Auditor</a></li>';
                                                                }
                                                                else
                                                                {
                                                                    if($v->review_monev!='')
                                                                    {
                                                                        $aksi.=' <li><a href="javascript:publishpic1('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-send"></i> &nbsp;&nbsp;Publish Ke Auditor</a></li>';
                                                                    }
                                                                }
                                                                // $aksi.='<li><a href="javascript:rangkumantindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Rangkuman Tindak Lanjut</a></li>';
                                                            }

                                                        }
                                                        
                                                        $aksi.='</ul>
                                                        </div></div>';
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center" style="font-size:11px !important;">{{$no}}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$tem!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$tgl!!}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$pic2!!}</td>
                                        <td class="text-left" style="font-size:11px !important;">{!!$tindak_lanjut!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$rinc!!}</td>
                                        <td class="text-center" style="font-size:11px !important;">{!!$aksi!!}</td>
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