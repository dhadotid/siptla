<div class="row" style="padding:0 10px;">
    <div class="col-md-12" style="border:1px solid #bbb;border-radius:5px;padding:5px 20px 0px 20px;background:#eee;">

            <h4 style="margin:0px;padding:0px;margin-bottom:10px;">Data LHP</h4>
            <div class="row" style="margin:0px;padding:0px;margin-bottom:10px;">
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                     <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Nomor LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="nomor_lhp" placeholder="Nomor LHP" id="nomor_lhp" value="{{$data->no_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Kode LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="kode_lhp" placeholder="Kode LHP" id="kode_lhp" value="{{$data->kode_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin:0px;padding:0px;margin-bottom:10px;">
                    <div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <label for="exampleTextInput1" class="col-sm-12 control-label text-left" style="font-size:10px;font-style:italic">Judul LHP:</label>
                        <div class="col-sm-12">
                            <input type="text" class="" name="judul_lhp" placeholder="Judul LHP" id="judul_lhp" value="{{$data->judul_lhp}}" readonly style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;background:#eee !important;width:100%">
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top:20px;">
        <table class="table table-bordered table-striped" id="review-lhp" style="width:100%">
            <thead>
                <tr class="primary">
                    <th class="text-center">No</th>
                    <th class="text-center">Review</th>
                    <th class="text-center" style="width:200px">Auditor Senior</th>
                    @if (Auth::user()->level=='auditor-senior')
                        <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($review as $key=>$item)
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td class="text-left">{!!$item->review!!}</td>
                        <td class="text-left">{!!$item->reviewer->name!!}</td>
                        @if (Auth::user()->level=='auditor-senior')
                            <td class="text-center">
                                @if ($data->status_lhp=='Review LHP')
                                    <a style="height:unset !important;" class="btn btn-xs btn-primary rounded" onclick="editformreviewlhp({{$idlhp}},{{$item->review_id}})">
                                        <div class="tooltipcss"><i class="glyphicon glyphicon-edit"></i>
                                            <span class="tooltiptext">Edit</span>
                                        </div>
                                    </a>
                                    <a style="height:unset !important;" class="btn btn-xs btn-danger rounded btn-delete-rekomendasi" onclick="hapusrekomendasi({{$idlhp}},{{$item->review_id}})">
                                        <div class="tooltipcss"><i class="glyphicon glyphicon-trash"></i>
                                            <span class="tooltiptext">Hapus</span>
                                        </div>
                                    </a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
