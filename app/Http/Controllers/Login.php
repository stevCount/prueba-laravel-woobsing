<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Login extends Controller
{
    use AuthenticatesUsers;
   
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->getIp = $_SERVER['HTTP_CLIENT_IP'];
    }

    public function redirectPath(){
        if(Auth::user()->tipo_usuario){ 
            $dateLastLogin = Auth::user()->last_login;
            $daysLastLogin = Carbon::createFromFormat('d/m/Y', $dateLastLogin)->format('l');
            $daysNow = Carbon::createFromFormat('d/m/Y', Carbon\Carbon::now())->format('l');
            if($daysLastLogin > $daysNow){
                return '/sesiones';
            }

            if(Auth::user()->tipo_usuario == 1 && $this->getIp == '127.0.0.1')
                Session::put('origin_sesion ', $this->getIp);
            return '/panel';
        }
        return '/home';
    }

    public function verifyTwoStep(Request $request){
        $curl = curl_init();
        $data = [
            "Content-Disposition" => "form-data",
            "account" => "123123123",
            "apiKey"=> "apiKeyToken",
            "token" => "tokenKey",
            "toNumber" => "3007292194",
            "sms" => "SMS de prueba",
            "flash" => 0,
            "isPriority" => 0,
            "sc" => 890202,
            "request_dlvr_rcpt" => 1
        ];
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api101.hablame.co/api/sms/v2.1/send/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Content-Type: multipart/form-data",
                "content-type: multipart/form-data; boundary=---011000010111000001101001"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "Error. " . $err;
        } else {
            if($response["status"] == "1x000"){
                echo "mensaje enviado con exito.";
            }
        }
    }
}
