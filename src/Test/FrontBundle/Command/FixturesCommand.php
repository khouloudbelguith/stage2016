<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 17/08/16
 * Time: 12:29 م
 */

namespace Test\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\EntityNotFoundException;
use DoctrineTest\InstantiatorTestAsset\ExceptionAsset;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\FrontBundle\Entity\Sheet;
use Test\FrontBundle\Entity\Repository;
use Test\FrontBundle\Form\Handler\SheetHandler;
use Test\FrontBundle\Form\Type\SheetType;



class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('demo:khouloud:load')
            ->setDescription('load fixtures ')
            ->setDefinition(array(
                //new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'Le nom du controller a creer'),
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'Le bundle dans lequel creer le controlleur'),
                new InputOption('basecontroller', '', InputOption::VALUE_REQUIRED, 'S\'il faut ou non heriter du controlleur de base de Symfony2'),
                new InputOption('entity', '', InputOption::VALUE_REQUIRED, 'nom de l\'entity')
            ))
            ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        //$dialog = $this->getDialogHelper ();
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Continue with this action?', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }
        $output->writeln(array(
            '',
            '      Bienvenue    ',
            '',
            'Cet outil va vous permettre de insérer dans la base de donnée dynamiquement ',
            '',
        ));

        $dialog = $this->getHelperSet()->get('dialog');
        /*$controller = $dialog->ask($output, 'What is the name of the Controller?');
        $input->getOption('controller');*/
        $entity = $dialog->ask($output, 'What is the name of the entity?');
        $input->getOption('entity');
        $basecontroller = $dialog->ask($output, 'is it extends base entity ? yes/no  ');
        $input->getOption('basecontroller');
        $bundle = $dialog->ask($output, 'What is the name of the bundle ou se trouve l\'entity?');
        $input->getOption('bundle');
        
        
        //$input->setOption('controller', $controller);
        $input->setOption('bundle', $bundle);
        $input->setOption('entity', $entity);
        $input->setOption('basecontroller', $basecontroller);
    }

    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        if ($input->isInteractive()) {
            $question = new ConfirmationQuestion('Do you confirm generation?', true);
            if (!$dialog->askConfirmation($output, $question->getQuestion(), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }
        // On recupere les options
        //$controller = $input->getOption('controller');
        $bundleName = $input->getOption('bundle');
        $basecontroller=$input->getOption('basecontroller');
        $entity = $input->getOption('entity');
        

       
        $output->writeln('koukou');
        
        // On recupere les infos sur le bundle nécessaire à la génération du controller
        $kernel = $this->getContainer()->get('kernel');
        //$bundle = $kernel->getBundle ($bundleName);
        $bundle = $kernel->getBundle ('AppBundle');
        $namespace = $bundle->getNamespace();
        $path = $bundle->getPath();
        $target = $path.'/DataFixtures/ORM/'.'Load'.$entity.'.php';
        $className = 'Load'.$entity;



        // On génère le contenu du controlleur
        $twig = $this->getContainer()->get ('templating');

        $controller_code = $twig->render('TestFrontBundle:DataCommand:Data.php.twig',
            array (
                //'controller' => $controller,
                'basecontroller' => $basecontroller,
                'namespace' => $namespace,
                'entity' => $entity,
                'bundleName' => $bundleName
            )
        );

        // On crée le fichier
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        file_put_contents($target, $controller_code);

        return 0;

        $this->generateClass($className,$target);
    }
    

    


}