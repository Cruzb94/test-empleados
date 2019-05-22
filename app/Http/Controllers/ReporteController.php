<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Response;
use App\User;
use App\Reporte;
use Auth;

class ReporteController extends Controller
{

    public function index()
    {
        
        if (Auth::user()->nivel == 'Administrador') {
            $reportes = Reporte::with('user')->get();
        }else{
            $reportes = Reporte::where('id_user',Auth::user()->id)->get();
        }

        return view('empleado.reportes.index', compact('reportes'));
    }

     public function nuevoReporte(Request $request){      
        $hoy = date("Y-m-d H:i:s"); 
        $id = DB::table('reportes')->insertGetId(
              [ 'id_user' => $request->id_user,
                'descripcion' => $request->descripcion,
                'created_at' => $hoy,
                'updated_at' => $hoy
              ]
          );
        
           return Response::json(array('status' => 'correcto', 'id' => $id, 'fecha' => $hoy));
        
    }

    public function editarReporte(Request $request){      
        $hoy = date("Y-m-d H:i:s"); 
        $item = Reporte::find($request->id_reporte);
        $item->fill(['descripcion' => $request->descripcion, 'updated_at' => $hoy]);
        $item->save();
        
        return Response::json(array('status' => 'correcto', 'fecha' => $hoy));
     
    }

    public function destroy(Request $request){
        $item = Reporte::find($request->id_reporte);
        $item->delete();

         return Response::json(array('status' => 'correcto'));
    }
}
