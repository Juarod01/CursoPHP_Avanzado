<?php

namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as v;

class UserController extends BaseController{
  public function getAddUserAction($request){
    $ResponseMessage = null;

    if($request->getMethod() == 'POST'){
      $postData = $request->getParsedBody();
      $userValidator = v::key('user', v::stringType()->notEmpty())
                  ->key('password', v::stringType()->notEmpty())
                  ->key('mail', v::stringType()->notEmpty());

      try{
        $userValidator->assert($postData);
        $postData = $request->getParsedBody();

        $pass = password_hash($postData['password'], PASSWORD_DEFAULT);

        $user = new User();
        $user->name = $postData['user'];
        $user->password = $pass;
        $user->mail = $postData['mail'];
        $user->save();

        $ResponseMessage = 'Saved';
      } catch(\Exception $e){
        $ResponseMessage = $e->getMessage();
      }

      
    }

    return $this->renderHTML('addUser.twig', [
      'ResponseMessage' => $ResponseMessage
    ]);
  }
}