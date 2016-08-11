<?php

/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 22/07/16
 * Time: 03:10 م
 */
namespace  Test\FrontBundle\Command;
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

class HelloCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')
            ->setDefinition(array(
                /* new InputOption('table', '', InputOption::VALUE_REQUIRED, 'Le nom de la table '),
                 new InputOption('entity', '', InputOption::VALUE_REQUIRED, 'entity concerned')
                 //new InputOption('basecontroller', '', InputOption::VALUE_REQUIRED, 'S\'il faut ou non heriter du controlleur de base de Symfony2')
           */
                new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'Le nom du controller a creer'),
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'Le bundle dans lequel creer le controlleur'),
                new InputOption('basecontroller', '', InputOption::VALUE_REQUIRED, 'S\'il faut ou non heriter du controlleur de base de Symfony2')
            ))
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addArgument(
                'prenom',
                InputArgument::OPTIONAL,
                'your last name'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
            ->addOption(
                'g',
                null,
                InputOption::VALUE_NONE,
                'green text'
            )
            ->addOption(
                'rbg',
                null,
                InputOption::VALUE_NONE,
                'white text on a red background'
            );
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
        /*$entity = $dialog->ask($output, 'What is the name of the entity?');
        $input->getOption('entity');
        $table = $dialog->ask($output, 'What is the name of the table?');
        $input->getOption('table');
        $input->setOption('entity', $entity);
        $input->setOption('table', $table);
        //$output->writeln($input->getOption('entity'));*/
        $controller = $dialog->ask($output, 'What is the name of the Controller?');
        $input->getOption('controller');
        $bundle = $dialog->ask($output, 'What is the name of the bundle?');
        $input->getOption('bundle');
        $basecontroller = $dialog->ask($output, 'What is the name of the basecontroller?');
        $input->getOption('basecontroller');
        $input->setOption('controller', $controller);
        $input->setOption('bundle', $bundle);
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
        /*$entity = $input->getOption('entity').'()';
        $table = $input->getOption('table');
        $name = $input->getArgument('name');
        $prenom = $input->getArgument('prenom');*/
        $controller = $input->getOption('controller');
        $bundleName = $input->getOption('bundle');
        $basecontroller=$input->getOption('basecontroller');
        $name = $input->getArgument('name');
        $prenom = $input->getArgument('prenom');

        if ($name && $prenom) {
            $text = 'good morning ' . $name . ' ' . $prenom;
        } else {
            $text = 'good morning';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
        if ($input->getOption('g')) {
            $text = '<info>' . $text . '</info>';
        }
        if ($input->getOption('rbg')) {
            $text = '<error>' . $text . '</error>';
        }

        $output->writeln($text);
        //return $output->$this->validateName('demo:greet');
        //  $output->

        /*$dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog->askContinuation($output,'<question>Valider la fiche ?</question>'))
        {
            continue;
        }*/


        /*
            //public function createAction(Request $request)
            //{
                $form = $this->createFormBuilder(new $entity)//un passage d'identité
                    ->add('name', null, array('label' => 'Nom de l\'album '))
                    ->add('type', null, array('label' => 'Type de l\'album'))
                    ->add('artist', null, array('label' => 'Artist'))
                    ->add('duration', null, array('label' => 'Duration de l\'album'))
                    ->add('released', 'date', array('label' => 'Date de l\'album'))
                    ->add('submit', 'submit')// les add pour la personalisation
                    ->getForm();//pour récupérer le formulaire
                //c'est la logique :il faut le déplacer

                $form->handleRequest($request);
                if ($request->isMethod('post') && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($form->getData());
                    $em->flush();
                    // $formHandler= new SheetHandler($this->createForm(new SheetType(),new Sheet()),$request);

                    return $output->$this->render('TestFrontBundle:Sheet:create.html.twig', array('form' => $form->getForm()->createView()));

                }*/

        // On recupere les infos sur le bundle nécessaire à la génération du controller
        $kernel = $this->getContainer()->get('kernel');
        $bundle = $kernel->getBundle ($bundleName);
        $namespace = $bundle->getNamespace();
        $path = $bundle->getPath();
        $target = $path.'/Controller/'.$controller.'Controller.php';

        // On génère le contenu du controlleur
        $twig = $this->getContainer()->get ('templating');

        $controller_code = $twig->render('TestFrontBundle:ControllerCommand:Controller.php.twig',
            array (
                'controller' => $controller,
                'basecontroller' => $basecontroller,
                'namespace' => $namespace
            )
        );

        // On crée le fichier
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        file_put_contents($target, $controller_code);

        return 0;


    }


}


