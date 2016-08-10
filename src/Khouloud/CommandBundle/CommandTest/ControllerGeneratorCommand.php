<?php

/**
 * Created by PhpStorm */
namespace Khouloud\CommandBundle\CommandTest;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerGeneratorCommand extends ContainerAwareCommand
{
   public function configure()
   {
        $this->setName('please:e5dem')
             ->setDescription('TESTER la commande')
            ;
   }
    public function execute(InputInterface $input,OutputInterface $output)
    {
        
        $output->writeln('hello');
    }

}