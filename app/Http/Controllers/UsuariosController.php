<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Response;
use App\User;

class UsuariosController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.usuarios.index', compact('users'));
    }

     public function nuevoUsuario(Request $request){      
    
        $usuario=DB::table('users')
                    ->where('email', $request->email)
                    ->get();
        
        if(!empty($usuario[0])){
           return Response::json(array('status' => 'error'));
        }else{
        
        $hoy = date("Y-m-d H:i:s"); 
        
        $id = DB::table('users')->insertGetId(
              [ 'name' => $request->nombre,
                'nivel' => 'Empleado',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => $hoy,
                'updated_at' => $hoy
              ]
          );
        
           return Response::json(array('status' => 'correcto', 'id' => $id));
        }  
    }

    public function editarUsuario(Request $request){      
    
        $item = User::find($request->id_user);
        $item->fill(['name' => $request->nombre, 'email' => $request->email]);
        $item->save();
        
        return Response::json(array('status' => 'correcto'));
     
    }

    public function destroy(Request $request){
        $item = User::find($request->id_user);
        $item->delete();

         return Response::json(array('status' => 'correcto'));
    }
}
