<div class="row">
    <div class="col-md-12 form-horizontal" style="margin-top:10px;">
        <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Nomor Temuan:</label>
            <div class="col-sm-8">
                <input readonly type="text" class="form-control custom-input" name="nomor_temuan" placeholder="Nomor Temuan" value="{{$data->no_temuan}}" id="detail_nomor_temuan">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Temuan:</label>
            <div class="col-sm-8">
                <textarea readonly class="form-control custom-input"  name="temuan" placeholder="Temuan" id="detail_temuan"></textarea>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Jenis Temuan:</label>
            <div class="col-sm-8">
               <input readonly type="text" class="form-control custom-input"  class="form-control"  name="jenistemuan"  placeholder="Jenis Temuan" id="detail_jenistemuan">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">PIC Temuan:
            </label>
            <div class="col-sm-8">
                <input readonly type="text" class="form-control custom-input"  class="form-control"  name="pic_temuan"  placeholder="PIC Temuan" id="detail_pic_temuan">
            </div>
        </div>
       <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Nominal:</label>
            <div class="col-sm-8">
                <input readonly type="text" class="form-control custom-input"  class="form-control"  name="nominal"  placeholder="Nominal" id="detail_nominal">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:15px !important;">
            <label for="exampleTextInput1" class="col-sm-4 control-label text-right">Level Resiko:</label>
            <div class="col-sm-8">
                <input readonly type="text" class="form-control custom-input"  class="form-control"  name="levelresiko"  placeholder="Level Resiko" id="detail_levelresiko">
            </div>
        </div>
       
    </div>
</div>