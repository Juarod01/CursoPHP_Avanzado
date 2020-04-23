<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use App\Models\Message;

class SendMailCommand extends Command
{
  protected static $defaultName = 'app:send-mail';

  protected function execute(InputInterface $input, OutputInterface $output)
    {
      $pendingMessage = Message::where('sent', false)->first();
      if($pendingMessage){
        $transport = (new Swift_SmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT')))
          ->setUsername(getenv('SMTP_USER'))
          ->setPassword(getenv('SMTP_PASS'))
        ;
  
        $mailer = new Swift_Mailer($transport);
  
        $message = (new Swift_Message('Wonderful Subject'))
          ->setFrom(['contact@contact.com' => 'Contact Person'])
          ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
          ->setBody('Hi, you hace a new message. Name' . $pendingMessage['name'] 
          . 'mail' . $pendingMessage['email'] . 'Message' . $pendingMessage['message'])
          ;
  
        $result = $mailer->send($message);
        $pendingMessage->sent = true;
        $pendingMessage->save();
        return 0;
      }
    }
}