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

    $transport = (new Swift_SmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT')))
      ->setUsername(getenv('SMTP_USER'))
      ->setPassword(getenv('SMTP_PASS'))
    ;

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message('Wonderful Subject'))
      ->setFrom(['contact@contact.com' => 'Contact Person'])
      ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
      ->setBody('Hi, you hace a new message. Name' . $requestData['name'] 
      . 'mail' . $requestData['email'] . 'Message' . $requestData['message'])
      ;

    $result = $mailer->send($message);
    return new RedirectResponse('/contact');
  }
}