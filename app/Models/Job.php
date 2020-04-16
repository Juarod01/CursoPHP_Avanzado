<?php
namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model; //Para traer la información de la base de datos.

class Job extends Model{ //Extendemos la clase de a libreria.
  use HasDefaultImage;

  protected $table = 'jobs'; //Llamamos la tabla de la BBDD
}