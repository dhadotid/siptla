<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Nomor LHP:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nomor_lhp" placeholder="Nomor LHP" id="edit_nomor_lhp" readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Pemeriksa:</label>
            <div class="col-sm-8">
                <select name="pemeriksa" class="form-control" id="edit_pemeriksa" data-plugin="select2" onchange="generatekodelhp(this.value)">
                    <option value="">-- Pilih --</option>
                    @foreach ($pemeriksa as $item)
                        <option value="{{$item->id}}-{{$item->code}}-{{$item->pemeriksa}}">{{$item->code}} - {{$item->pemeriksa}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Kode LHP:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control"  name="kode_lhp" placeholder="AAA/000/{{date('Y')}}" id="edit_kode_lhp">
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Judul LHP:</label>
            <div class="col-sm-8">
                <textarea class="form-control"  name="judul_lhp" placeholder="Judul LHP" id="edit_judul_lhp"></textarea>
            </div>
        </div>
    
        <div class="form-group">
            <label for="datetimepicker2" class="col-sm-4 control-label text-right">Tanggal LHP</label>
            <div class="col-sm-8">
                <div class='input-group date' id='datetimepicker2' data-plugin="datepicker" data-date-format="dd/mm/yyyy">
                    <input type='text' class="form-control" name="tanggal_lhp" id="edit_tanggal_lhp" readonly value="{{date('d/m/Y')}}"/>
                    <span class="input-group-addon bg-info text-white">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Tahun Pemeriksaan:</label>
            <div class="col-sm-3">
                <select name="tahun_pemeriksaan" class="form-control" id="edit_tahun_pemeriksaan" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @for ($i = date('Y'); $i >= (date('Y')-20); $i--)
                        @if (date('Y')==$i)
                            <option value="{{$i}}" selected="selected">{{$i}}</option>
                        @else
                            <option value="{{$i}}">{{$i}}</option>
                        @endif
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Jenis Audit/Review:</label>
            <div class="col-sm-8">
                <select name="jenis_audit" class="form-control" id="edit_jenis_audit" data-plugin="select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($jenisaudit as $item)
                        <option value="{{$item->id}}">{{$item->jenis_audit}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if (Auth::user()->level=='auditor-junior')
            
            <div class="form-group">
                <label for="datetimepicker2" class="col-sm-4 control-label text-right">Status LHP</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control edit_status_lhp"  name="status_lhp" placeholder="Status LHP" id="edit_status_lhp" value="Create LHP" readonly>
                    <input type="hidden" class="form-control"  name="flag_status_lhp" placeholder="Status LHP" id="edit_flag_status_lhp" value="1">
                </div>
            </div>
        @else
            <div class="form-group">
                <label for="datetimepicker2" class="col-sm-4 control-label text-right">Status LHP</label>
                <div class="col-sm-8">
                    {{-- <input type="text" class="form-control"  name="status_lhp" placeholder="Status LHP" id="status_lhp" value="Create LHP" readonly> --}}
                    <select class="form-control edit_status_lhp_select" id="edit_status_lhp" data-plugin="select2" name="status_lhp">
                        <option value="Create LHP">Create LHP</option>
                        @if (Auth::user()->level=='super-user')
                        <option value="Review LHP">Review LHP</option>
                        <option value="Publish LHP">Publish LHP</option>
                        @endif
                    </select>
                    {{-- <input type="hidden" class="form-control"  name="flag_status_lhp" placeholder="Status LHP" id="flag_status_lhp" value="0"> --}}
                </div>
            </div>
            <div class="form-group" style="display:none">
                <label for="datetimepicker2" class="col-sm-4 control-label text-right">Review LHP</label>
                <div class="col-sm-8">
                    <textarea class="form-control"  name="review_lhp" placeholder="Review LHP" id="edit_review_lhp"></textarea>
                </div>
            </div>
        @endif
    </div>
</div>
