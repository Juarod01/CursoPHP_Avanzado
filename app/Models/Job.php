<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model; //Para traer la información de la base de datos.

class Job extends Model{ //Extendemos la clase de a libreria.
  protected $table = 'jobs'; //Llamamos la tabla de la BBDD
}