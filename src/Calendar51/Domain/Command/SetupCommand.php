<?php

namespace Calendar51\Domain\Command;

use Calendar51\Domain\Event\SchemaUpdate;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupCommand
 *
 * @package Calendar51\AppBundle\Command
 */
class SetupCommand extends ContainerAwareCommand
{
    /**
     * SetupCommand constructor.
     *
     * @param SchemaUpdate $schemaUpdateService
     */
    public function __construct(SchemaUpdate $schemaUpdateService)
    {
        parent::__construct(null);
        $this->schemaUpdateService = $schemaUpdateService;
    }

    /**
     * Configures the current command.
     */
    public function configure()
    {
        $this->setName('calendar51:schema:update')
            ->setDescription('This command will update the database structure across the app.')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'SQL file used for schema update.'
            );
    }

    /**
     * Update the schema.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $fileName = $input->getArgument('filename');

            $this->schemaUpdateService->update($fileName);

            $output->writeln('Schema was updated successfully!');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
