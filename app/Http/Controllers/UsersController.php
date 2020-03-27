<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDinas;
use App\Models\PivotUserDinas;
use App\Models\PICUnit;
use App\User;
use Validator;
use Auth;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($level=null)
    {
        if($level==null)
            $user=User::orderBy('name')->get();
        else
            $user=User::where('level',$level)->orderBy('name')->get();
        $picunit=PICUnit::orderBy('nama_pic')->get();
        // dd($user[1]->user);
        $jenislevel=jenis_level();
        return view('backend.pages.user.index')
                ->with('users',$user)
                ->with('level',$level)
                ->with('picunit',$picunit)
                ->with('jenislevel',$jenislevel);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required',
            'level' => 'required',
            'password' => 'required|confirmed',
        ])->validate();

        $insert = new User;

        if($request->input('level')=='pic-unit') 
        {
            list($picunit_id,$picunit_name)=explode('__',$request->name_pic);
            $insert->name = $picunit_name;
            $insert->pic_unit_id = $picunit_id;
        }
        else
        {
            $insert->name = $request->name;
        }
        $insert->email = $request->email;
        $insert->password = bcrypt($request->password);
        $insert->level = $request->level;
        $insert->telepon = $request->telepon;
        $insert->flag = $request->flag;
        $insert->save();
        
        if($request->input('level')=='pic-unit') 
        {
            $picunit=PICUnit::find($picunit_id);
            $picunit->id_user=$insert->id;
            $picunit->save();
        }

        return redirect()->route('pengguna.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        $pisunit=PICUnit::where('id_user',$id)->first();
        $data['name']=$user->name;
        $data['nip']=$user->nip;
        $data['email']=$user->email;
        $data['telepon']=$user->telepon;
        $data['level']=$user->level;
        $data['flag']=$user->flag;
        $data['created_at']=$user->created_at;
        $data['updated_at']=$user->updated_at;
        $data['deleted_at']=$user->deleted_at;
        $data['pic_unit_id']=$user->pic_unit_id;
        $data['nama_pic']=$pisunit->nama_pic;
        $data['picunit']=$pisunit->id.'__'.$pisunit->nama_pic;
        return $data;
    }

    public function update(Request $request, $id)
    {
        if (is_null($request->password) || is_null($request->password_confirmation)) {
            Validator::make($request->all(), [
                // 'name' => 'required',
                'email' => 'required',
                'level' => 'required',
            ])->validate();

            $update = User::find($id);
            if($request->input('level')=='pic-unit') 
            {    
                list($picunit_id,$picunit_name)=explode('__',$request->name_pic);
                $update->name = $picunit_name;
                $update->pic_unit_id = $picunit_id;
                
                $picunit=PICUnit::find($picunit_id);
                $picunit->id_user=$id;
                $picunit->save();
            }
            else
            {
                $update->name = $request->name;
            }
            $update->email = $request->email;
            $update->level = $request->level;
            $update->flag = $request->flag;
            $update->telepon = $request->telepon;
            $update->save();

            return redirect()->route('pengguna.index')
                ->with('success', 'Anda telah mengubah data pengguna.');
        }

        Validator::make($request->all(), [
            // 'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'password' => 'required|confirmed',
        ])->validate();

        $update = User::find($id);
        if($request->input('level')=='pic-unit') 
        {
            
            list($picunit_id,$picunit_name)=explode('__',$request->name_pic);
            $update->name = $picunit_name;
            $update->pic_unit_id = $picunit_id;

            $piunit=PICUnit::find($picunit_id);
            $picunit->id_user=$id;
            $picunit->save();
        }
        else
        {
            $update->name = $request->name;
        }
        $update->email = $request->email;
        $update->password = bcrypt($request->password);
        $update->level = $request->level;
        $update->telepon = $request->telepon;
        $update->flag = $request->flag;
        $update->save();

        return redirect()->route('pengguna.index')
            ->with('success', 'Anda telah mengubah data pengguna.');
    }

    public function destroy($id)
    {
        $us=User::find($id);
        $us->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Anda telah menghapus data pengguna.');
    }

    public function profil()
    {
        $iduser=Auth::user()->id;
        $user=User::find($iduser);
        $picunit=PICUnit::where('id_user',$iduser)->with('levelpic')->orderBy('nama_pic')->first();
        return view('backend.pages.user.profil')
                ->with('iduser',$iduser)
                ->with('user',$user)
                ->with('picunit',$picunit);
    }
    public function simpan_profil(Request $request,$id)
    {
        $iduser=Auth::user()->id;
        $user=User::find($iduser);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->telepon=$request->telepon;

        if($request->password!='')
        {
            $user->password=bcrypt($request->password);
        }

        $s=$user->save();
        if($s)
            return redirect()->route('pengguna.profil')
            ->with('success', 'Anda telah mengubah data pengguna.');
        else
            return redirect()->route('pengguna.profil')
            ->with('error', 'Mengubah data pengguna tidak berhasil.');
    }
}
