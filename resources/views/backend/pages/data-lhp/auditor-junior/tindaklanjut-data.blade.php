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
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                    $tem=$tgl=$aksi=$pic2='';
                                @endphp
                                @foreach ($temuan as $idtemuan=>$item)
                                    @php
                                        $norekom=$tglselesai=$aksi=$rincian='';
                                        $tem='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Temuan</i></small><br>
                                            <b>'.$item->no_temuan.'</b>
                                            </div>';
                                        $tem.='<div class="col-md-10"><small><i>Temuan</i></small><br>
                                            <b>'.$item->temuan.'</b>
                                            </div>';
                                        $tem.='</div>';
                                        $tgl='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $pic2='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        $aksi='<div class="row" style="height:75px;border-bottom:1px dotted #ddd">&nbsp;</div>';
                                        
                                        
                                        $tem.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $tem.='<div class="col-md-2"><small><i>No. Rekomendasi</i></small></div>';
                                        $tem.='<div class="col-md-10"><small><i>Rekomendasi</i></small></div>';
                                        $tem.='</div>';

                                        $tgl.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $tgl.='<div class="col-md-2">&nbsp;</div>';
                                        $tgl.='<div class="col-md-10">&nbsp;</div>';
                                        $tgl.='</div>';

                                        $pic2.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
                                        $pic2.='<div class="col-md-2">&nbsp;</div>';
                                        $pic2.='<div class="col-md-10">&nbsp;</div>';
                                        $pic2.='</div>';
                                        
                                        $aksi.='<br><div class="row" style="height:35px;border-bottom:1px dotted #ddd">';
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

                                                $tem.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">';
                                                $tem.='<div class="col-md-1 text-center">'.$v->nomor_rekomendasi.'</div>';
                                                $tem.='<div class="col-md-11">'.$text_rekom.'</div>';
                                                $tem.='</div>';
                                                

                                                if($v->pic_2_temuan_id!='')
                                                    $pic2.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:150px;"><div class="col-md-12 text-center">'.(isset($pic[$v->pic_2_temuan_id]) ? $$pic[$v->pic_2_temuan_id]->nama_pic : '').'</div></div>';
                                                else
                                                    $pic2.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:150px;"><div class="col-md-12 text-center">'.(isset($pic[$v->pic_2_temuan_id]) ? $$pic[$v->pic_2_temuan_id]->nama_pic : '-').'</div></div>';

                                                $tgl.='<div style="height:60px;"  id="tgl_penyelesaian_'.$item->id_temuan.'_'.$v->id.'">';
                                                if($v->tanggal_penyelesaian!='')
                                                {
                                                    $tgl.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><span class="label label-info"><i class="fa fa-calendar"></i> '.tgl_indo($v->tanggal_penyelesaian).'</span></div>
                                                        </div>';
                                                    // $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                }
                                                else
                                                {
                                                   $tgl.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12"><i class="label label-danger">Belum Di Set</i></div>
                                                        </div>';
                                                }
                                                $tgl.='</div>';
                                                    $user_pic=\App\Models\PICUnit::where('id_user',Auth::user()->id)->first();
                                                    $styleaksi='display:none';
                                                    
                                                    $aksi.='<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0;width:80px;">
                                                        <div class="btn-group" id="aksi_rekomendasi_'.$item->id_temuan.'_'.$v->id.'">
                                                            <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                                <li>
                                                                    <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$v->id_lhp.'__'.$item->id_temuan.'_0__'.$v->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                                </li>
                                                                <li><a href="javascript:detailtindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>';

                                                                $aksi.='<li><a href="javascript:rangkumantindaklanjut('.$v->id.')" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Rangkuman Tindak Lanjut</a></li>';

                                                        
                                                        $aksi.='</ul>
                                                        </div></div>';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$no}}</td>
                                        <td class="text-left">{!!$tem!!}</td>
                                        <td class="text-center">{!!$tgl!!}</td>
                                        <td class="text-left">{!!$pic2!!}</td>
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