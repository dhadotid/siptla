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
                                    <th class="text-center">No Temuan</th>
                                    <th class="text-center">Temuan</th>
                                    <th class="text-center">No. Rekomendasi</th>
                                    <th class="text-center">Rekomendasi</th>
                                    <th class="text-center">Tanggal<br>Penyelesaian</th>
                                    <th class="text-center">PIC 2</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($temuan as $item)
                                    @if($item->totemuan->tahun_pemeriksa==$tahun)
                                        @php
                                        
                                            $rekom=$norekom=$tglselesai=$aksi=$pic2='';
                                            if(isset($rekomendasi[$item->id]))
                                            {
                                                foreach($rekomendasi[$item->id] as $key=>$val)
                                                {
                                                    $norekom.=$val->nomor_rekomendasi.'<br>';
                                                    $rekom.='<li style="height:32px;">- '.(strlen($val->rekomendasi) > 30 ? substr($val->rekomendasi,0,30).' ...' : $val->rekomendasi ).'</li>';

                                                    if(isset($val->picunit2->nama_pic))
                                                        $pic2.='<li style="height:32px;">'.$val->picunit2->nama_pic.'</li>';
                                                    else
                                                        $pic2.='<li style="height:32px;">-</li>';

                                                    $tglselesai.='<div id="tgl_penyelesaian_'.$item->id.'_'.$val->id.'">';
                                                        if($val->tanggal_penyelesaian!='')
                                                        {
                                                            $tglselesai.='<li style="height:32px;">'.tgl_indo($val->tanggal_penyelesaian).'</li>';
                                                        }
                                                        else
                                                        {
                                                            $tglselesai.='<li style="height:32px;">
                                                                <div class="input-group date" id="datetimepicker2" >
                                                                    <input type="text" data-plugin="datepicker" data-date-format="dd/mm/yyyy" class="form-control" name="tanggal_penyelesaian" id="tanggal_penyelesaian_'.$item->id.'_'.$val->id.'" value="'.date('d/m/Y').'" style="height:30px !important;width:120px !important;min-width:120px !important; "/>
                                                                    <span class="input-group-addon bg-info text-white" style="cursor:pointer" onclick="settglpenyelesaian('.$item->id.','.$val->id.')"><i class="glyphicon glyphicon-ok-sign"></i> Set</span>
                                                                </div>    
                                                            </li>';
                                                        }
                                                    $tglselesai.='</div>';
                                                    $aksi.='<li style="margin-bottom:1px;height:32px;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" style="height:28px;"><i class="fa fa-bars"></i></button>
                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" style="height:28px;">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="right:0 !important;left:unset !important">
                                                            <li>
                                                                <a href="#" class="btn-add" data-toggle="modal" data-target="#modaltambahtindaklanjut" data-value="'.$item->id_lhp.'__'.$item->id.'_0__'.$val->id.'_0'.'" style="font-size:11px;"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Tambah Tindak Lanjut</a>
                                                            </li>
                                                            <li><a href="#" target="_blank" style="font-size:11px;"><i class="glyphicon glyphicon-list"></i> &nbsp;&nbsp;Detail Tindak Lanjut</a></li>
                                                        </ul>
                                                    </div></li>';
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{$no}}</td>
                                            <td class="text-center">{{$item->no_temuan}}</td>
                                            <td class="text-left">{{(strlen($item->temuan) > 30 ? substr($item->temuan,0,30).' ...' : $item->temuan )}}</td>
                                            <td class="text-center">{!!$norekom!!}</td>
                                            <td class="text-left"><ul>{!!$rekom!!}</ul></td>
                                            <td class="text-center"><ul>{!!$tglselesai!!}</ul></td>
                                            <td class="text-left"><ul>{!!$pic2!!}</ul></td>
                                            <td class="text-center"><ul>{!!$aksi!!}</ul></td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
<link rel="stylesheet" href="{{asset('theme/backend/libs/misc/datatables/datatables.min.css')}}"/>
<script src="{{asset('theme/backend/libs/misc/datatables/datatables.min.js')}}"></script>
<script>
    $('#table').DataTable();
</script>