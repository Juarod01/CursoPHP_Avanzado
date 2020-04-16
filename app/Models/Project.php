<?php

namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model; //Para traer la información de la base de datos.

class Project extends Model{
  use HasDefaultImage;
  protected $table = 'projects'; //Llamamos la tabla de la BBDD
}