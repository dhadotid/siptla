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
                                <th class="text-center" rowspan="2">Bidang Pengawasan<br>No & Tgl LHP</th>
                                <th class="text-center" rowspan="2">Temuan / Penyebab<br>(Uraian Ringkas)</th>
                                <th class="text-center" colspan="2">Kode</th>
                                <th class="text-center" rowspan="2">Rekomendasi<br>(Uraian Ringkas)</th>
                                <th class="text-center" rowspan="2">Kode Rekomendasi</th>
                                @if (Auth::user()->level==3)
                                    <th class="text-center" rowspan="2">Tanggapan</th>
                                @else
                                    <th class="text-center" rowspan="2">Status</th>
                                @endif
                                <th class="text-center" rowspan="2">Harus Selesai<br>Tanggal</th>
                                <th class="text-center" rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th class="text-center">Temuan</th>
                                <th class="text-center">Sebab</th>
                            </tr>
                        </thead>

                        {{-- <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        Audit Kinerja <br>
                                        700.138/08-Insp/I/2017 <br>
                                        29 Mei 2017
                                    </td>
                                    <td>
                                        Terdapat 2 orang Pejabat belum membuat Program Kerja, yaitu sebagai berikut:
                                        <br>
                                        - Kepala Sub Bidang Keuangan <br>
                                        - Kepala Sub Bidang Administratif <br>
                                        <br>
                                        Penyebab: <br>
                                        Pejabat yang bersangkutan belum menaati peraturan yang berlaku.
                                    </td>
                                    <td>03</td>
                                    <td>104</td>
                                    <td>
                                        Kepala Dinas secara tertulis memerintahkan agar segera membuat Program Kerja Tahunan untuk Tahun Anggaran 2017.
                                    </td>
                                    <td>050</td>
                                    
                                    @if (Auth::user()->level==3)
                                        <td><i style="color:red;">Belum Ada</i></td>
                                    @else
                                        <td><i style="color:green;">Sudah Verifikasi</i></td>
                                    @endif
                                    <td><button class="btn btn-xs btn-info" style="height:24px !important;"><i class="fa fa-calendar"></i> 21/12/2018</button></td>
                                    <td style="display:flex;">
                                        @if (Auth::user()->level==3)
                                            <span data-toggle="tooltip" data-title="Tanggapan">
                                                <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-xs btn-success" style="height:24px !important;">
                                                    <i class="fa fa-volume-up"></i>
                                                </a>
                                            </span>&nbsp;
                                            <span data-toggle="tooltip" data-title="Detail">
                                                <a class="btn btn-xs btn-primary" style="height:24px !important;">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if (Auth::user()->level==1)
                                            <span data-toggle="tooltip" data-title="Verifikasi">
                                                <a href="" class="btn btn-xs btn-success" style="height:24px !important;">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            </span>&nbsp;
                                        @endif
                                        @if (Auth::user()->level==2)
                                            <span data-toggle="tooltip" data-title="Ubah">
                                                <a href="" class="btn btn-xs btn-warning" style="height:24px !important;">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </span>&nbsp;
                                            <span data-toggle="tooltip" data-title="Hapus">
                                                <a href="" class="btn btn-xs btn-danger" style="height:24px !important;">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                        </tbody> --}}
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

					$('#name').val(res.name)
					$('#nip').val(res.nip)
					$('#email').val(res.email)
					$('#password').val(res.password)
					$('#password_confirmation').val(res.password)
					$('#level').val(res.level)
					$('#flag').val(res.flag)
					$('#dinas_id').val(res.user.dinas_id)
                    $('#pangkat').val(res.pangkat)
					$('#golongan').val(res.golongan)
					$('#jabatan').val(res.jabatan)
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