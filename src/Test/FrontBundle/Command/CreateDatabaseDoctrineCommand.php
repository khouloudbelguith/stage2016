<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 15/08/16
 * Time: 04:08 م
 */

namespace Test\FrontBundle\Command;


use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Input\InputArgument;
/**
 * Database tool allows you to easily drop and create your configured databases.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class CreateDatabaseDoctrineCommand extends DoctrineCommand
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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('demo:fixtures:load')
            ->setDescription('khouloud : Load a dataset of fixtures.')
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
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getDoctrineConnection($input->getOption('connection'));
        $params = $connection->getParams();
        if (isset($params['master'])) {
            $params = $params['master'];
        }
        $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);
        if (!$name) {
            throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
        }
        unset($params['dbname']);
        $tmpConnection = DriverManager::getConnection($params);
        // Only quote if we don't have a path
        if (!isset($params['path'])) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }
        $error = false;
        try {
            $tmpConnection->getSchemaManager()->createDatabase($name);
            $output->writeln(sprintf('<info>Created database for connection named <comment>%s</comment></info>', $name));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not create database for connection named <comment>%s</comment></error>', $name));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $error = true;
        }
        $tmpConnection->close();
        return $error ? 1 : 0;
    }
}