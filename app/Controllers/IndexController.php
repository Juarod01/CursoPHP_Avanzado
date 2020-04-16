<?php 

namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController extends BaseController{
  public function indexAction(){

    $jobs = Job::all(); //Nos trae todos los registros que encuentre en la tabla de la DDBB
    $projects = Project::all();

    /*$limitMonths = 15;
    $jobs = array_filter($jobs->toArray(), function ($job) use ($limitMonths){  //closure -- se añade 'use' para poder utilizar la variable
        return $job['months'] >= $limitMonths;
    });*/

    $name = 'Juan David Rodriguez';

    //include '../Views/index.php';
    return $this->renderHTML('/index.twig', [
      'name' => $name,
      'jobs' => $jobs,
      'projects' => $projects
    ]);
  }
}