<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Helpers;

use Firebase\JWT\Jwt;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
    public $key;
    public function __construct() {
        $this->key='esta_es_una_clave_super_secreta-12345678';
    }
    // devuelve el usuario o el token
    public function signup($email,$password,$getToken=null)
    {
        
        //Buscar si existe el usuario  con sus credenciales
        $user   = User::where([
                  'email'=>$email,
                  'password'=>$password
        ])->first();
        //Comprobar si son correctos(objeto)
        $signup=false;
        if(is_object($user))
        {
            $signup=true;//si los datos llegaron correctamente tru
        }
        //Generar el token con los datos del usuario identificad
        if($signup)
        {
           $token=array(
               'sub'      => $user->id, // id del registro del usuario
               'email'    => $user->email,
               'name'     => $user->name,
               'surname'  => $user->surname,
               'iat'      => time(), // fecha cuando se ha cread0
               'exp'      =>time()+(7*24*60*60)// dentro una semana el token caduca
           );
//utilizo la libreria JWT , el key los sabe solamente el backend, agregro una key en esta clase  
           $jwt=JWT::encode($token, $this->key,'HS256'); // el algoritmo de codificacion
           $decoded=JWT::decode($jwt, $this->key,['HS256']); //
           
           //devolver los datos decodificados
           // si el token es null que devuelva el token
           if(is_null($getToken))
           {
               $data= $jwt;
           }
           else
           {
               $data= $decoded;
           }
           
        }
        else
        {
            $data=array(
                'status'=>'error',
                'message'=>'Login incorrecto'
            );
        }
        //Devolver los datos decodificados o el token en funcion de un parametro   
        return $data;
    } 
    //$jwt EL TOKEN que queremos decodificar
    //$getIdentity si viene true devuelve el usuario(un objeto con todos sus datos)
    public function checkToken($jwt,$getIdentity=false)
    {
        $auth=false;
        
        try
        {
            $jwt= str_replace('"', '', $jwt);//limipiar las comillas delante y detras del token
            
            $decoded=JWT::decode($jwt, $this->key,['HS256']);//decodifica el token
        }
        catch(\UnexpectedValueException $e)
        {
             $auth=false;
        }
        catch(\DomainException $e)
        {
             $auth=false;
        }
        //sino esta vacio decoded, si es objeto y si existe sub
        if(!empty($decoded) && is_object($decoded)&& isset($decoded->sub))
        {//autenticacion correcta
            $auth=true;
        }
        else
        {//autenticacion incorrecta
            $auth=false;
        }
        if($getIdentity)//si la llaven identity devolvera el token decodificado
        {
            return $decoded;
        }
        return $auth;
    }

}
