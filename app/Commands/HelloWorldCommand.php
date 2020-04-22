<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
  protected static $defaultName = 'app:hello-world';

  public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'name');
    }

  protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn('Hola ' . $input->getArgument('name'));
        return 0;
    }
}