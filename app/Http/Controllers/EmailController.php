<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        try{
            Mail::send('email', ['nama' => $request->nama, 'pesan' => $request->pesan], function ($message) use ($request)
            {
                $message->subject($request->judul);
                $message->from('donotreply@gmail.com', 'SIPTLA');
                $message->to($request->email);
            });
            return response(['status' => true, 'message' => 'Berhasil terkirim']);
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }
}
