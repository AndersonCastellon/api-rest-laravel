<?php
/**
 * Created by PhpStorm.
 * User: Anderson
 * Date: 10/8/2019
 * Time: 20:55
 */

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
    private $KEY = '45$cXTJ*b5xt96TBpmP2ZsBTnnR5DeDP#vc9tx8Zu8M6j!CV7rc%J5Q9*@fE8C$4aQ@ptG#jGKMRxDhQeZ4!!FGat2U362yzS
    *7bm!P3TPY6$@nXNsB5#n3py@jn*NFReuS6AXE4k#@SkA8WJAZ@SpGbD485F9hdRqhEqQ@J*u2gW5sc2jX#84SG*4b*^v83fg%9ZX*!
    qZVDAt4u5Sj8pKQd2Z3U9QTup^29#BWBXfHD3$m8jF8QFxeX7V2SUf5aZ&y$ZEpYbGmfrJ6^H9hEsX4j98vGY457FJh^TyND$9pW
    Rm#G2Q242Y96k4&JnXj4EfZ8fBgh3*6WTJdq%7cdWBW7Zu#@HtTYCd5MeYKXD*gzbT*2FsjW#59h4fH96*8cPrVKJjQQD$zXGm9R
    3#nvTBsgn5DmHPYrnQZYD38zdb55H624M!xXJ5N^M4!YfJu3Rv%ACF^BRJZMNG$BBxB55uh3sYZ7n24#H!97FaY9577E2%xWz6F9';

    public function logIn($email, $password, $getToken = true)
    {
        //Buscar si existe el usuario por sus credenciales
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //Comprobar si son correctas (Si devuelve un objeto entonces los datos del user existen)
        $logIn = false;
        if (is_object($user)) {
            $logIn = true;
        }

        //Generar el token con los datos del usuario identificado
        if ($logIn) {
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            );

            is_null($token) ? null : $jwt = JWT::encode($token, $this->KEY, 'HS256');
            is_null($jwt) ? null : $decoded = JWT::decode($jwt, $this->KEY, ['HS256']);

            //devolver los datos decodificados o el token, en funcion de un parámetro
            if ($getToken) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados son incorrectos'
            );
        }

        return $data;
    }

    public function checktoken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"','',$jwt);
            $decoded = JWT::decode($jwt, $this->KEY, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub))
        {
            $auth = true;
        }

        if ($getIdentity)
        {
            return $decoded;
        }

        return $auth;
    }
}