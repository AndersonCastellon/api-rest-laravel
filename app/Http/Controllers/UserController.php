<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function test(Request $request)
    {
        return 'Pruebas para el controlador ' . __CLASS__;
    }

    public function register(Request $request)
    {

        // Recoger los datos del usuario

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            $validate = \Validator::make($params_array,
                [
                    'name' => 'required|alpha',
                    'surname' => 'required|alpha',
                    'email' => 'required|email|unique:users',
                    'password' => 'required'
                ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Usuario no registrado',
                    'errors' => $validate->errors()
                );
            } else {

                //Cifrar la password
                $pwd = hash('sha256', $params_array['password']);

                //Crear nuevo usuario a registrar en la db
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';
                $user->save();

                $data = array(
                    'status' => 'succes',
                    'code' => 200,
                    'message' => 'Usuario registrado correctamente',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Has enviado datos incorrectos'
            );
        }


        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        //Recibir los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        //Validar los datos
        if (!empty($params_array)) {
            $validate = \Validator::make($params_array,
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Usuario no registrado',
                    'errors' => $validate->errors()
                );
            } else {
                //Cifrar la contraseña
                $pwd = hash('sha256', $params_array['password']);
                //Devolver el token
                if (isset($params_array['getToken'])) {
                    if ($params_array['getToken'] == 'true') {
                        $data = $jwtAuth->login($params_array['email'], $pwd);
                    } else {
                        $data = $jwtAuth->login($params_array['email'], $pwd, false);
                    }
                } else {
                    $data = $jwtAuth->login($params_array['email'], $pwd);
                }
            }

        }

        return response()->json($data);
    }

    public function update(Request $request)
    {

        //Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            //Obtener usuario identificado
            $token = $request->header('Authorization');
            $jwtAuth = new JwtAuth();
            $currentUser = $jwtAuth->checktoken($token, true);

            //Validar datos
            $validate = \Validator::make($params_array,
                [
                    'name' => 'required|alpha',
                    'surname' => 'required|alpha',
                    'email' => 'required|email|unique:users,' . $currentUser->sub
                ]);

            //Quitar los campos que no se actualizaran
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //Actualizar los datos en la base de datos
            $updateUser = User::where('id', $currentUser->sub)->update($params_array);

            //Retornar array con resultado
            if ($updateUser) {
                $data = array(
                    'code' => 200,
                    'status' => 'succes',
                    'user' => $currentUser,
                    'changes' => $params_array
                );
            }
        }

        return response()->json($data, $data['code']);
    }

    public function uploadAvatar(Request $request)
    {

        //Recoge los datos de la petición
        $image = $request->file('file0');

        //Guardar imagen
        if ($image) {

            $nameImage = time() . $image->getClientOriginalName();
            \Storage::disk('users')->put($nameImage, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'succes',
                'image' => $nameImage
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            );
        }

        return response()->json($data, $data['code']);
    }
}
