<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model; //Para traer la información de la base de datos.

class User extends Model{ //Extendemos la clase de a libreria.
  protected $table = 'users'; //Llamamos la tabla de la BBDD
}