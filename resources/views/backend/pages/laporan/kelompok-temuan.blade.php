@extends('backend.layouts.master')

@section('title')
    <title>Data Temuan</title>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
                <div class="col-md-8">
                    <span class="widget-title">Laporan Per Kelompok Temuan Tahun 2018</span>
                </div>
                <div class="col-md-2">
                    @php
                        $tahunloop = $tahun - 2;
                    @endphp
                    <select name="tahun" class="form-control pull-right" id="pilih_tahun">
                        @for ($i = $tahunloop; $i < $tahun + 3; $i++)
                            <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>Tahun {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('print-kelompok-temuan', $tahun) }}" target="_blank" class="btn btn-primary">Cetak Laporan</a>
                </div>
			</header><!-- .widget-header -->
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" data-plugin="DataTable">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Rekomendasi Temuan</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Jumlah Kejadian</th>
                                <th class="text-center">Persentase</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($temuan as $key => $item)
                                <tr>
                                    <td>{{ $key = $key + 1 }}</td>
                                    <td>{{ $item->temuan }}</td>
                                    <td style="text-align:center;">{{ $item->code }}</td>
                                    <td style="text-align:center;">
                                        @php
                                            $countkejadian = 0;
                                        @endphp
                                        @foreach ($kejadian as $k)
                                            @if ($k->temuan_id==$item->id)
                                                @php
                                                    $countkejadian = $k->jumlah_kejadian
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        {{ $countkejadian }}
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($totalkejadian!=0)
                                            {{ round($countkejadian/$totalkejadian*100, 2) }} %
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        @php
                                            $countkerugian = 0;
                                        @endphp
                                        @foreach ($nilai as $k)
                                            @if ($k->temuan_id==$item->id)
                                                @php
                                                    $countkerugian = $k->nilai_kerugian
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        {{ number_format($countkerugian, 0) }}
                                    </td>
                                </tr>
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

        $('#pilih_tahun').change(function(){
            var tahun = $(this).val()
            window.location.href = "{{ url('laporan-kelompok-temuan') }}" + "/" + tahun
        })
	</script>
@endsection