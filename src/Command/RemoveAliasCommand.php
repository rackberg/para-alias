<?php

namespace ParaAlias\Command;

use ParaAlias\Configuration\AliasConfigurationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveAliasCommand
 *
 * @package ParaAlias\Command
 */
class RemoveAliasCommand extends Command
{
    /**
     * The alias configuration.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * {@inheritdoc}
     */
    public function __construct(AliasConfigurationInterface $aliasConfiguration)
    {
        parent::__construct();

        $this->aliasConfiguration = $aliasConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('alias:remove')
            ->setDescription('Removes a configured alias.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the alias.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aliasName = $input->getArgument('name');
        $this->aliasConfiguration->removeAlias($aliasName);
        $this->aliasConfiguration->save();

        $output->writeln(sprintf(
            '<info>The alias "ls" has been removed successfully.</info>',
            $aliasName
        ));
    }
}
