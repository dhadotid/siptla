<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDinas;
use App\Models\PivotUserDinas;
use App\Models\PICUnit;
use App\User;
use Validator;
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
            'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'password' => 'required|confirmed',
        ])->validate();

        $insert = new User;

        if($request->input('level')=='pic-unit') 
        {
            
            $insert->name = $request->name_pic;
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
        
        return redirect()->route('pengguna.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return User::where('id',$id)->first();
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
                $update->name = $request->name_pic;
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
            
            $update->name = $request->name_pic;
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
}
