<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDinas;
use App\Models\PivotUserDinas;
use App\User;
use Validator;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::where('level','!=',4)->with('user')->get();
        // dd($user[1]->user);
        $dinas=MasterDinas::all();
        return view('backend.pages.user.index')->with('users',$user)->with('dinas',$dinas);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'dinas_id' => 'required',
            'pangkat' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
            'password' => 'required|confirmed',
        ])->validate();

        $insert = new User;
        $insert->name = $request->name;
        $insert->email = $request->email;
        $insert->password = bcrypt($request->password);
        $insert->level = $request->level;
        $insert->nip = $request->nip;
        $insert->pangkat = $request->pangkat;
        $insert->golongan = $request->golongan;
        $insert->jabatan = $request->jabatan;
        $insert->flag = $request->flag;
        $insert->save();
        $user_id=$insert->id;

        $u_dinas = new PivotUserDinas;
        $u_dinas->dinas_id = $request->dinas_id;
        $u_dinas->user_id = $user_id;
        $u_dinas->flag = $request->flag;
        $u_dinas->save();

        return redirect()->route('users.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return User::where('id',$id)->with('user')->first();
    }

    public function update(Request $request, $id)
    {
        if (is_null($request->password) || is_null($request->password_confirmation)) {
            Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'level' => 'required',
                'dinas_id' => 'required',
                'pangkat' => 'required',
                'golongan' => 'required',
                'jabatan' => 'required',
            ])->validate();

            $update = User::find($id);
            $update->name = $request->name;
            $update->email = $request->email;
            $update->level = $request->level;
            $update->nip = $request->nip;
            $update->pangkat = $request->pangkat;
            $update->golongan = $request->golongan;
            $update->jabatan = $request->jabatan;
            $update->flag = $request->flag;
            $update->save();

            $u_dinas = PivotUserDinas::where('user_id',$id)->first();
            $u_dinas->dinas_id = $request->dinas_id;
            $u_dinas->flag = $request->flag;
            $u_dinas->save();

            return redirect()->route('users.index')
                ->with('success', 'Anda telah mengubah data pengguna.');
        }

        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'dinas_id' => 'required',
            'pangkat' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
            'password' => 'required|confirmed',
        ])->validate();

        $update = User::find($id);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->password = bcrypt($request->password);
        $update->level = $request->level;
        $update->nip = $request->nip;
        $update->pangkat = $request->pangkat;
        $update->golongan = $request->golongan;
        $update->jabatan = $request->jabatan;
        $update->flag = $request->flag;
        $update->save();

        $u_dinas = PivotUserDinas::where('user_id',$id)->first();
        $u_dinas->dinas_id = $request->dinas_id;
        $u_dinas->flag = $request->flag;
        $u_dinas->save();

        return redirect()->route('users.index')
            ->with('success', 'Anda telah mengubah data pengguna.');
    }

    public function destroy($id)
    {
        $us=User::find($id);
        $us->delete();

        $u_dinas = PivotUserDinas::where('user_id',$id)->first();
        $u_dinas->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Anda telah menghapus data pengguna.');
    }
}
