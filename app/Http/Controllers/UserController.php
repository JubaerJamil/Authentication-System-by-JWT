<?php

namespace App\Http\Controllers;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    function userregistration(Request $request){
        Try{
                User::create($request->input());
            return response()->json([
                "status" => "success",
                "message" => "Registrations successfully"
            ],status:200);
        }
        catch(Exception $e){
            return response()->json([
                "status" => "Faild",
                "message" => "Registrations Failed"
            ],status:500);
        };
    }

    function userlogin(Request $request){
        $response = User::where($request->input())->count();
        if ($response === 1){
            $token = JWTToken::createtoken($request->input('email'));
            return response()->json(['message' => 'success', 'data' => $token]);
        }
        else{
        return response()->json(['message' => 'faild', 'data' => 'unauthorized']);
        }
    }

    function sendotpcode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();
        if ($count === 1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', '=', $email)->update(['otp'=>$otp]);
            return response()->json([
                "status" => "success",
                "message" => "Send OTP Mail Successfully"
            ]);
        }
        else {
            return response()->json([
                "status" => "failed",
                "message" => "unathorized"
            ]);
        }
    }

    function Otpverify (Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if($count === 1){
                //update DB password
            User::where('email', '=', $email)->update(['otp'=>0]);

                // JWT token
            $token = JWTToken::createtokenForresetpassword($request->input('email'));
            return response()->json([
                "status" => "Success",
                "message" => "Verified your OTP token",
                "token" => $token
            ],status:200);
        }
        else
        {
            return response()->json([
                "status" => "Failed",
                "message" => "unothorized"
            ],status:500);
        }
    }

    function SetPassword(Request $request){
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=', $email)->update(['password'=>$password]);

            return response()->json([
                "status" => "success",
                "message" => "Password update successfully"
            ],200);
        }
        catch (Exception $exception)
        {
            return response()->json([
                "status" => "Field",
                "message" => "Something went wrong"
            ],401);
        }
    }


}
