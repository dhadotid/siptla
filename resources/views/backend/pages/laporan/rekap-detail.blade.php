@extends('backend.layouts.master')

@section('title')
    <title>Detail Rekap Temuan</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
                <header class="widget-header" style="margin-top:0px !important;padding-top:0px !important;">
                    <a href="{{url('rekap-temuan')}}" class="btn btn-success btn-xs pull-right">&lt;&lt; Kembali</a>
                </header>
                <span class="widget-title">Detail Rekap Temuan Pada OPD : {{$dinas->nama_dinas}}</span>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" data-plugin="DataTable">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width:15px;">#</th>
                                <th class="text-center" rowspan="2">Bidang Pengawasan<br>No & Tgl LHP</th>
                                <th class="text-center" rowspan="2">Temuan<br>(Uraian Ringkas)</th>
                                <th class="text-center" rowspan="2">Kode Temuan</th>
                                <th class="text-center" rowspan="2">Rekomendasi<br>(Uraian Ringkas)</th>
                                <th class="text-center" rowspan="2">Kode Rekomendasi</th>
                                
                                <th class="text-center" rowspan="2">Status</th>
                                
                            </tr>
                        </thead>

                        <tbody>
                        @php
                            $no=1;
                        @endphp
                        @foreach ($daftar as $uss)
                            @if (isset($det[$uss->id]))
                            
                                @foreach ($det[$uss->id] as $key=>$us)
                                
                                    <tr>
                                        <td>{{ $key = $key + 1 }}</td>
                                        <td>
                                                {{$uss->pengawasan->bidang}}
                                                <br><br>
                                                No : {{$uss->no_pengawasan}}<br>
                                                Tgl : {{date('d/m/Y',strtotime($uss->tgl_pengawasan))}}<br>
                                           
                                        </td>
                                        <td>
                                            {!! $us->uraian_temuan !!}
                                            
                                        </td>
                                        <td class="text-center">{{$us->temuan->code}}</td>
                                        <td>{!! $us->uraian_rekomendasi !!}</td>
                                        <td class="text-center">{{$us->rekomendasi->code}}</td>
                                        <td class="text-center">{!!$hasil!!}</td>
                                    </tr>
                                @endforeach
                            @endif
                    @endforeach
                        </tbody>
                    </table>
				</div>
			</div><!-- .widget-body -->
		</div><!-- .widget -->
	</div>
@endsection

@section('footscript')
	<script>
		// binding data to modal edit
        $('#table').on('click', '.btn-edit', function(){
            var id = $(this).data('value')
			
            $.ajax({
                url: "{{ url('users') }}/"+id+"/edit",
                success: function(res) {
					$('#form-update').attr('action', "{{ url('users') }}/"+id)

					
                }
            })
        })

		// delete action
        $('#table').on('click', '.btn-delete', function(){
            var id = $(this).data('value')
			$('#form-delete').attr('action', "{{ url('users') }}/"+id)			
        })
	</script>
@endsection