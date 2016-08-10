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
        parent::configure();
        $this->setName('please:e5dem')
             ->setDescription('TESTER la commande')
            ;
   }
    public function execute(InputInterface $input,OutputInterface $output)
    {
        parent::execute($input, $output);
        $output->writeln('hello');
    }

}