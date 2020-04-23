<?php   

namespace App\Controllers;

use App\Models\Message;
use App\Models\user;
use Respect\Validation\Validator as v;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;

Class ContactController extends BaseController
{
  public function index(){
    return $this->renderHTML('contact/index.twig');
  }
  public function send(ServerRequest $request){
    $requestData = $request->getParsedBody();
    $message = new Message();
    $message->name = $requestData['name'];
    $message->email = $requestData['email'];
    $message->message = $requestData['message'];
    $message->sent = false;
    $message->save();

    
    return new RedirectResponse('/contact');
  }
}