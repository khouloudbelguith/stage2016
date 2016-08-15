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
/**
 * Database tool allows you to easily drop and create your configured databases.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class CreateDatabaseDoctrineCommand extends DoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('khouloud:database:create')
            ->setDescription('Creates the configured databases')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
            ->setHelp(<<<EOT
The <info>doctrine:database:create</info> command creates the default
connections database:
<info>php app/console doctrine:database:create</info>
You can also optionally specify the name of a connection to create the
database for:
<info>php app/console doctrine:database:create --connection=default</info>
EOT
            );
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