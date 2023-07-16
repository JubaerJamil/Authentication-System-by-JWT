<?php
namespace App\Helper;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Faker\Extension\Helper;

class JWTToken{

    public static function createtoken($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*60,
            'userEmail' => $userEmail
        ];
        return JWT::encode($payload, $key, 'HS256');
    }


    public static function createtokenForresetpassword($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*10,
            'userEmail' => $userEmail
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function decodetoken($token):string{

        try{
            $key = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return $decode->userEmail;
        }
        catch(Exception $e){
            return 'unathorized';
        }

    }

}
