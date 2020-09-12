<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    public function register(Request $request)
    {
        
      //recoger los datos de usuario mediante un archivo json
        $json=$request->input('json',null);
        
        //decodificar
        $params= json_decode($json);//obtengo un objeto
        $params_array= json_decode($json, true);//obtengo un arreglo
        
        if(!empty($params)&& !empty($params_array))
        {
            //validacion de los valores del json
            $validate=\Validator::make($params_array,[
                'name'=>'required|alpha',
                'surname'=>'required|alpha',
                'email'=>'required|email|unique:users',
                'password'=>'required'
            ]);
            if($validate->fails())
            {
                $data= array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'El usuario no se ha creado',
                    'errors'=>$validate->errors()
                );
            }
            else
            {
                //cifrar la contrasena
              //  $pwd = password_hash($params->password, PASSWORD_BCRYPT,['cost'=>4]);
               $pwd = hash('sha256',$params->password);
                //crear el usuario
                $user=new User();
                $user->name= $params_array['name'];
                $user->surname= $params_array['surname'];
                $user->email= $params_array['email'];
                $user->password=$pwd;
                $user->role='ROLE_USER';
                //guardar el usuario en la base de datos
                $user->save();
                //valores de la respuesta
                $data= array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'El usuario se ha creado correctamente',
                    'errors'=>$validate->errors()
                );
                
            }
        }
        else
        {
            $data= array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'Los datos enviados no son correctos',
                    'errors'=>$validate->errors()
                );
        }
      return response()->json($data,$data['code']);

    }
    public function  login(Request $request)
    {
        $jwtAuth = new \JwtAuth();
        
        //Recibir datos por POST ,null en caso no nos llegue
        $json= $request->input('json',null);
        $params= json_decode($json);
        $params_array= json_decode($json,true);

        //Validar los datos recibidos
        $validate=\Validator::make($params_array,[
                   'email'     =>  'required|email',    
                   'password'  =>  'required'
        ]);

        if($validate->fails())
        {//la validacion ha fallado
            $signup=array(
              'status'   =>  'error',
              'code'     =>  404,
              'message'  =>   'El usuario no se ha podido identificar ',
              'errors'   =>    $validate->errors()
            );
        }
        else
        {
           //Cifrar el password
           $pwd= hash('sha256', $params->password);

           //Devolver token o datos  usando el metdo signup 
           $signup=$jwtAuth->signup($params->email,$pwd);
           if(!empty($params->getToken))
           {//true devuelve los datos decodificados
               $signup=$jwtAuth->signup($params->email,$pwd,true);
           }
        }  
        return response()->json($signup,200);
      //TOKEN "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjYsImVtYWlsIjoiZXZlbGluQGdtYWlsLmNvbSIsIm5hbWUiOiJFdmVsaW4iLCJzdXJuYW1lIjoiQWxhIiwiaWF0IjoxNTk5NTY2NzMwLCJleHAiOjE2MDAxNzE1MzB9.BxcybchjjXZm4CbPIa2oY3hY6f6bj2pAd-Rq_AxEN-U"  
        /* VIDEO 10
       // echo $jwtAuth->signup();
        $email='evelin@gmail.com';
        $password='evelin';
        //$pwd = password_hash($password, PASSWORD_BCRYPT,['cost'=>4]);
        $pwd = hash('sha256',$password);
        //var_dump($pwd);die();
       // return $jwtAuth->signup($email,$pwd);
        return response()->json($jwtAuth->signup($email,$pwd,true),200);
         * 
         */
    }
    public function  pruebaPost(Request $request)
    {
        return "entro a la prueba Post ".$request;
    }
    
     public function update(Request $request)
    {   //comprobamos que el usuario este identificado
        $token=$request->header('Authorization');
        $jwtAuth=new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        
        //Actualizar usuario
        $json= $request->input('json',null);
        $params_array= json_decode($json, true);//obtengo un arreglo
        
        if($checkToken && !empty($params_array))
        {

            
            // obtener el usuario autentificado
            $user = $jwtAuth->checkToken($token,true);
            //var_dump($user); die();
            
            //validacion de los valores del json
            $validate=\Validator::make($params_array,[
                'name'=>'required|alpha',
                'surname'=>'required|alpha',
                'email'=>'required|email|unique:users,'.$user->sub
            ]);
            
            // quitar los campos que no se va a actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);
            
            //actulizar el usuario en la base de datos
            $user_update=User::where('id',$user->sub)->update($params_array);
            
            //echo "<h1>Login Correcto</h1>";
                    $data= array(
                    'code'=>200,
                    'status'=>'success en actualizar usuario',
                    'message'=>'El usuario si se ha actualizado',
                    'user'=>$user,
                    'changes'=>$params_array,
                );
        }
        else
        {
            //echo "<h1>Login INCORRECTO</h1>";
            $data= array(
                    'code'=>400,
                    'status'=>'error en la autenticacion para actualizar',
                    'message'=>'El usuario no esta autentificado',
                );
        }
        return response()->json($data,$data['code']);
    }
    public function upload(Request $request)
    { }
}
