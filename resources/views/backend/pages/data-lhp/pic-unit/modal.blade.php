<div class="modal fade" id="modaltambahtindaklanjut" role="dialog">
		<div class="modal-dialog modal-lg" id="modal-size">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_tindaklanjut_add" action="{{url('tindaklanjut-unitkerja-simpan')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Data Tindak Lanjut </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="left-div" class="col-md-12">
                                <div id="konten-add-form"></div>
                            </div>
                            <div id="right-div" class="col-md-0">
                                <a href="javascript:closerightdiv()" class="btn btn-xs btn-danger pull-right" id="close-btn" style="display: none"><i class="fa fa-close"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="submit" onclick="" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>

    <div class="modal fade" id="modaledittindaklanjut" role="dialog" style="z-index:1000000 !important;">
		<div class="modal-dialog" id="modal-size">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="form_tindaklanjut_edit"  enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit Tindak Lanjut </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="left-div" class="col-md-12">
                                <div id="konten-edit-form"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="submit" onclick="" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>


{{-- Modal Rincian Sewa--}}
 <div class="modal fade" id="modalrinciansewa" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrinciansewa">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Pembayaran Sewa</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-sewa"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiformsewa()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Sewa--}}
{{-- Modal Rincian Sewa--}}
 <div class="modal fade" id="modalrincianuangmuka" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianuangmuka">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Pengembalian Sisa Uang Muka </h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-uangmuka"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiformuangmuka()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Sewa--}}
{{-- Modal Rincian Sewa--}}
 <div class="modal fade" id="modalrincianlistrik" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianlistrik">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Biaya Listrik</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-listrik"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiformlistrik()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Sewa--}}
{{-- Modal Rincian Piutang--}}
 <div class="modal fade" id="modalrincianpiutang" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianpiutang">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Piutang</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-piutang"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiformpiutang()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Piutang--}}
{{-- Modal Rincian Piutang Karyawan--}}
 <div class="modal fade" id="modalrincianpiutangkaryawan" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianpiutangkaryawan">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Piutang Karyawan</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-piutangkaryawan"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiformpiutangkaryawan()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Piutang Karyawan--}}
{{-- Modal Rincian Hutang Titipan--}}
 <div class="modal fade" id="modalrincianhutangtitipan" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianhutangtitipan">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Tindak Lanjut Hutang Titipan</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-hutangtitipan"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasihutangtitipan()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Hutang Titipan--}}
{{-- Modal Rincian Penutupan Rekening--}}
 <div class="modal fade" id="modalrincianpenutupanrekening" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianpenutupanrekening">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Penutupan Rekening</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-penutupanrekening"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasipenutupanrekening()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Penutupan Rekening--}}
{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrincianumum" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
                <form action="" method="POST" class="form-horizontal" id="formrincianumum">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi (Umum -Setoran)</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-umum"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasiumum()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}

{{-- Modal Rincian--}}
 <div class="modal fade" id="modalrincian" role="dialog">
		<div class="modal-dialog modal-lg" style="width:90%;">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Rincian Tindak Lanjut</h4>
                </div>
                <div class="modal-body">
                    <div id="table-rincian"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                </div>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}
{{-- Modal TIndal Lanjut--}}
 <div class="modal fade" id="lihattindaklanjut" role="dialog">
		<div class="modal-dialog modal-lg" style="width:70% !important">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Data Tindak Lanjut</h4>
                </div>
                <div class="modal-body">
                    <div id="table-data-tindaklanjut"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                </div>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}

{{-- Modal TIndal Lanjut--}}
 <div class="modal fade" id="addtindaklanjutrincian" role="dialog" style="z-index:100000 !important">
		<div class="modal-dialog">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modaltitleaddtindaklanjutrincian">Rincian Tindak Lanjut</h4>
                </div>
                <form method="POST" class="form-horizontal" action="{{ route('simpan-tindaklanjut-rincian') }}" id="form_tindaklanjut_rincian" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div id="form-tindaklanjut-rincian"></div>
                        <br>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow=""
                            aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                0%
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="isupdate" id="isupdate" value="0">
                    <input type="hidden" name="idtindaklanjut" id="idtindaklanjut">
                    <input type="hidden" name="totalnilai" id="totalnilai">
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}
{{-- Modal TIndal Lanjut  style="z-index:100000 !important" --}}
 <div class="modal fade" id="listtindaklanjutrincian" role="dialog">
		<div class="modal-dialog modal-lg" style="width:90%;">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail Tindak Lanjut</h4>
                </div>
                    <div class="modal-body">
                        <div id="list-tindaklanjut-rincian"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                    </div>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}
{{-- Modal TIndal Lanjut--}}
 <div class="modal fade" id="listrinciantl" role="dialog">
		<div class="modal-dialog" style="width:60% !important">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Data Rincian Tindak Lanjut</h4>
                </div>
                    <div class="modal-body">
                        <div id="list-rincian"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                    </div>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}
 <div class="modal fade" id="rangkuman-tindaklanjut-rekomendasi" role="dialog">
		<div class="modal-dialog" style="width:70% !important">
			<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Rangkuman Data Tindak Lanjut</h4>
                </div>
                <form method="POST" class="form-horizontal" id="form_list_rangkuman" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div id="list-rangkuman"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-success">Tutup</button>
                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian--}}
{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrinciankontribusi"  role="dialog" style="z-index:10000 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formrinciankontribusi">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Kontribusi </h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-kontribusi"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasikontribusi()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}
{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrinciannonsetoranperjanjiankerjasama"  role="dialog" style="z-index:10000 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formrinciannonsetoranperjanjiankerjasama">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Perjanjian Kerjasama</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-nonsetoranperjanjiankerjasama"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasinonsetoranperjanjiankerjasama()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}
{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrinciannonsetoran"  role="dialog" style="z-index:10000 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formrinciannonsetoran">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Non Setoran</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-modalrinciannonsetoran"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasinonsetoran()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}

{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrinciannonsetoranumum"  role="dialog" style="z-index:10000 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formrinciannonsetoranumum">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Umum (Non Setoran)</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-modalrinciannonsetoranumum"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasinonsetoranumum()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
	</div>  
{{-- END Modal Rincian Umum--}}

{{-- Modal Rincian Umum--}}
 <div class="modal fade" id="modalrinciannonsetoranpertanggungjawabanuangmuka"  role="dialog" style="z-index:10000 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
                <form method="POST" class="form-horizontal" id="formrinciannonsetoranpertanggungjawabanuangmuka">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Rincian Nilai – Rekomendasi Pertanggungjawaban Uang Muka</h4>
                    </div>
                    <div class="modal-body">
                        <div id="form-rincian-modalrinciannonsetoranpertanggungjawabanuangmuka"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                        <input type="button" onclick="validasinonsetoranpertanggungjawabanuangmuka()" class="btn btn-success" value="Simpan">
                    </div>
				</form>
			</div>
		</div>
    </div>  
    
{{-- beberapa ini dihapus action="{{ url('rincian-simpan/'.$idlhp) }}" --}}
{{-- END Modal Rincian Umum--}}


