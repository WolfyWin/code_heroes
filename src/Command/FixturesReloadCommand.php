<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:fixtures:reload', description: 'Reload fixtures')]
class FixturesReloadCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fixtures:reload')

            // the short description shown while running "php bin/console list"
            ->setDescription('Drop/Create Database and load Fixtures ....')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to load dummy data by recreating database and loading fixtures...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = ['command' => 'doctrine:database:drop',"--force" => true];
        $application->run(new ArrayInput($options));


        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = ['command' => 'doctrine:database:create',"--if-not-exists" => true];
        $application->run(new ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
        ]);

        //Create de Schema
        $options = ['command' => 'doctrine:schema:update',"--force" => true];
        $application->run(new ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $options = ['command' => 'doctrine:fixtures:load',"--no-interaction" => true];
        $application->run(new ArrayInput($options));


        return 1;
    }
}