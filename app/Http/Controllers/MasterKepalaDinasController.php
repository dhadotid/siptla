<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKepalaDinas;
use App\Models\MasterDinas;
use App\User;
use Validator;
class MasterKepalaDinasController extends Controller
{
    public function index()
    {
        $opds=MasterDinas::orderBy('nama_dinas')->get();
        $kepala_opd=MasterKepalaDinas::with(['userkepala','dinas'])->orderBy('nama')->get();
        // dd($kepala_opd);
        return view('backend.pages.kepala-opd.index')->with('opds',$opds)->with('kepala_opd',$kepala_opd);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        Validator::make($request->all(), [
            'nama' => 'required',
            'nip' => 'required',
            'email' => 'required',
            'pangkat' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
            'dinas_id' => 'required'
        ])->validate();

        $mas=MasterKepalaDinas::where('dinas_id',$request->dinas_id)->with('userkepala')->get();
        foreach($mas as $k => $v)
        {
            if(isset($v->userkepala->level))
            {
                if($v->userkepala->level==4)
                {
                    $v->flag=0;
                    $v->save();
                }
            }
        }

        $user=User::where('nip',$request->nip)->first();
        if(is_null($user))
        {
            $us=new User;
            $us->name = $request->nama;
            $us->nip = $request->nip;
            $us->email = $request->email;
            $us->password = bcrypt($request->nip);
            $us->pangkat = $request->pangkat;
            $us->golongan = $request->golongan;
            $us->jabatan = $request->jabatan;
            $us->level = 4;
            $us->flag = 1;
            $us->save();

            $user_id=$us->id;
        }
        else
        {
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->pangkat = $request->pangkat;
            $user->golongan = $request->golongan;
            $user->jabatan = $request->jabatan;
            $user->level = 4;
            $user->flag = $request->flag;
            $user->save();

            $user_id=$user->id;
        }

        $insert = new MasterKepalaDinas;
        $insert->nama = $request->nama;
        $insert->dinas_id = $request->dinas_id;
        $insert->user_id = $user_id;
        $insert->flag = $request->flag;
        $insert->save();

        return redirect()->route('kepala-opd.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterKepalaDinas::where('id',$id)->with(['userkepala','dinas'])->first();
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'nama' => 'required',
            'nip' => 'required',
            'email' => 'required',
            'pangkat' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
            'dinas_id' => 'required'
        ])->validate();

        $update = MasterKepalaDinas::find($id);
        $update->nama = $request->nama;
        $update->dinas_id = $request->dinas_id;
        $update->flag = $request->flag;
        $update->save();

        $user=User::where('id',$update->user_id)->first(); 
        $user->name = $request->nama;
        $user->nip = $request->nip;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->pangkat = $request->pangkat;
        $user->golongan = $request->golongan;
        $user->jabatan = $request->jabatan;
        $user->level = 4;
        $user->flag = $request->flag;
        $user->save();
        
        return redirect()->route('kepala-opd.index')
            ->with('success', 'Anda telah memperbaharui data Kepala OPD.');
    }

    public function destroy($id)
    {
        $m=MasterKepalaDinas::where('id',$id)->first();
        $m->flag=0;
        $m->save();

        // $u=User::where('id',$m->user_id)->first();
        // $u->flag=0;
        // $u->save();
        
        return redirect()->route('kepala-opd.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
