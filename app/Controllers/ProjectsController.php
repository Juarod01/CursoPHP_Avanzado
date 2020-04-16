<?php

namespace App\Controllers;
use App\Models\Project;
use Respect\Validation\Validator as v;

class ProjectsController extends BaseController{
  public function getAddProjectAction($request){
    $ResponseMessage = null;

    if($request->getMethod() == 'POST'){
      $postData = $request->getParsedBody();
      $projectValidator = v::key('title', v::stringType()->notEmpty())
                  ->key('description', v::stringType()->notEmpty());

      try{
        $projectValidator->assert($postData);
        $postData = $request->getParsedBody();

        $files = $request->getUploadedFiles();
        $logo = $files['logo'];

        $filePath = "";
        if($logo->getError() == UPLOAD_ERR_OK){
          $fileName = $logo->getClientFilename();
          $filePath = "uploads/$fileName";
          $logo->moveTo($filePath);
        }

        $project = new Project();
        $project->title = $postData['title'];
        $project->description = $postData['description'];
        $project->logoName = $filePath;
        $project->save();

        $ResponseMessage = 'Saved';
      } catch(\Exception $e){
        $ResponseMessage = $e->getMessage();
      }

      
    }

    return $this->renderHTML('addProject.twig', [
      'ResponseMessage' => $ResponseMessage
    ]);
  }
}