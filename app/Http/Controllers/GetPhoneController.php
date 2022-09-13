<?php

namespace App\Http\Controllers;

use Auth;
use Botble\Bycode\Models\Bycode;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetPhoneController extends Controller
{
    public function index(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $name_app = '';
            $res = Http::get("http://b210910.otp.com.vn/api/phones/request?token=5fd3985a8766cba1a6ad058d56930e96&service=$id");

            if ($id == '28') {
                $name_app = 'Zalo';
            }
            if ($id == '90') {
                $name_app = 'Toss';
            }
            $bycode = Bycode::create([
                'name_app' => $name_app,
                'phone_number' => $res->json()['data']['phone_number'],
                'session' => $res->json()['data']['session'],
                'id_user'=>Auth::user()->id,
            ]);
            return response()->json($bycode);
        } catch (\Exception $e) {
            return response()->json('error', 404);
        }
    }
//    public function test(){
//
//        $byCodes= \App\Models\ByCode::whereNull('code')->get();
//        foreach ($byCodes as $code){
//            $res = Http::get("http://b210910.otp.com.vn/api/sessions/$code->session?token=5fd3985a8766cba1a6ad058d56930e96");
//            if(array_key_exists('messages',$res['data'])){
//                $code->code =  $res['data']['messages'][0]['otp'];
//                $code->save();
//            }
//        }
//
//        return response()->json('oke');
//    }
}
