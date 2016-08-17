<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 17/08/16
 * Time: 10:15 ص
 */

namespace Test\FrontBundle\Command;



use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Faker\Provider\Base as Provider;
use JBen87\DataBundle\Dataset\DatasetInterface;
use JBen87\DataBundle\DependencyInjection\Compiler\DatasetCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use AppBundle\DataFixtures\ORM;
use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class LoadFixturesCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var string
     */
    private $fixturesDir;
    /**
     * @var string
     */
    private $culture;
    /**
     * @var DatasetInterface[]
     */
    private $datasets = [];
    /**
     * @var Provider[]
     */
    private $providers = [];
    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];
   
    /**
     * @param string $name
     * @param DatasetInterface $dataset
     *
     * @see DatasetCompilerPass
     */
    public function setDataset($name, DatasetInterface $dataset)
    {
        $this->datasets[$name] = $dataset;
    }
    /**
     * @param string $name
     * @param ProcessorInterface $processor
     *
     * @see ProcessorCompilerPass
     */
    public function setProcessor($name, ProcessorInterface $processor)
    {
        $this->processors[$name] = $processor;
    }
    /**
     * @param string $name
     * @param Provider $provider
     *
     * @see ProviderCompilerPass
     */
    public function setProvider($name, Provider $provider)
    {
        $this->providers[$name] = $provider;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('demo:fixtures:test')
            ->setDescription('Load a dataset of fixtures.')
            ->addArgument(
                'dataset',
                InputArgument::REQUIRED,
                sprintf('The dataset to load.')
            )
            ->addOption(
                'append',
                null,
                InputOption::VALUE_NONE,
                'Append the data fixtures instead of deleting all data from the database first.'
            )
            ->addOption(
                'purge-with-truncate',
                null,
                InputOption::VALUE_NONE,
                'Purge data by using a database-level TRUNCATE statement'
            )
        ;
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->loadDoctrineCommand($input, $output);
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function loadDoctrineCommand(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('dataset');
        $this->log($output, sprintf('loading "%s" dataset', $name));
        DoctrineCommand:load(
            $this->entityManager
        );
    }
    

    /**
     * @param OutputInterface $output
     * @param string $message
     */
    private function log(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
    }
}