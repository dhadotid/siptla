@extends('backend.layouts.master')

@section('title')
    <title>Data Temuan</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
                <span class="widget-title">Data Temuan</span>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" data-plugin="DataTable">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width:15px;">#</th>
                                <th class="text-center" rowspan="2">Nama OPD</th>
                                <th class="text-center" rowspan="2">Total Temuan</th>
                                <th class="text-center" colspan="4">Status</th>
                            </tr>
                            <tr>
                                <th class="text-center">Baru Masuk</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">7 Hari Batas Akhir<br> Tindak Lanjut</th>
                                <th class="text-center">&gt; 60 Hari</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($dinas as $item)
                                <tr>
                                    <td class="text-center">{{$no}}</td>
                                    <td class="text-left"><a href="{{url('rekap-temuan-detail/'.str_slug($item->nama_dinas))}}">{{$item->nama_dinas}}</a></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" style="height:24px !important;padding-top:0px !important;">{{isset($d[$item->id]) ? count($d[$item->id]) : 0}}</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success" style="height:24px !important;padding-top:0px !important;">{{isset($baru[$item->id]) ? count($baru[$item->id]) : 0}}</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-default" style="height:24px !important;padding-top:0px !important;background:#ccc;">{{isset($selesai[$item->id]) ? count($selesai[$item->id]) : 0}}</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" style="height:24px !important;padding-top:0px !important;">{{isset($tujuh[$item->id]) ? count($tujuh[$item->id]) : 0}}</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-danger" style="height:24px !important;padding-top:0px !important;">{{isset($enampuluh[$item->id]) ? count($enampuluh[$item->id]) : 0}}</button>
                                    </td>
                                </tr>
                                @php
                                    $no++;
                                @endphp
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