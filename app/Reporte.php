<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reporte extends Authenticatable
{
  protected $table = 'reportes';
  protected $fillable = ['id', 'id_user', 'descripcion','created_at', 'updated_at'];

   public function user()

    {        

        return $this->belongsTo('App\User', 'id_user', 'id');

    }
}


