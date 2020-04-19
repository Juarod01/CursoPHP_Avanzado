<?php
namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator as v;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;

class JobsController extends BaseController{
  public function indexAction(){
    $jobs = Job::all();
    return $this->renderHTML('jobs/index.twig', compact('jobs'));
  }

  public function deleteAction(ServerRequest $request){
      $params = $request->getQueryParams();
      $job = Job::where('Id', $params['id'])->delete();


      return new RedirectResponse('/jobs');
  }

  public function getAddJobAction($request){
    $ResponseMessage = null;

    if($request->getMethod() == 'POST'){
      $postData = $request->getParsedBody();
      $jobValidator = v::key('title', v::stringType()->notEmpty())
                  ->key('description', v::stringType()->notEmpty());

      try
      {
        $jobValidator->assert($postData);
        $postData = $request->getParsedBody();

        $files = $request->getUploadedFiles();
        $logo = $files['logo'];

        $filePath = "";
        if($logo->getError() == UPLOAD_ERR_OK){
          $fileName = $logo->getClientFilename();
          $filePath = "uploads/$fileName";
          $logo->moveTo($filePath);
        }

        $job = new Job();
        $job->title = $postData['title'];
        $job->description = $postData['description'];
        $job->logoName = $filePath;
        $job->save();

        $ResponseMessage = 'Saved';
      } catch(\Exception $e){
        $ResponseMessage = $e->getMessage();
      }

      
    }

    return $this->renderHTML('addJob.twig', [
      'ResponseMessage' => $ResponseMessage
    ]);
  }
}