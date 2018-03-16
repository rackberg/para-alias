<?php

namespace ParaAlias\Command;

use ParaAlias\Configuration\AliasConfigurationInterface;
use ParaAlias\Factory\AliasFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddAliasCommand
 *
 * @package ParaAlias\Command
 */
class AddAliasCommand extends Command
{
    /**
     * The alias configuration.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * The alias factory.
     *
     * @var \ParaAlias\Factory\AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * AddAliasCommand constructor.
     *
     * @param \ParaAlias\Configuration\AliasConfigurationInterface $aliasConfiguration The alias configuration.
     * @param \ParaAlias\Factory\AliasFactoryInterface $aliasFactory The alias factory.
     */
    public function __construct(
        AliasConfigurationInterface $aliasConfiguration,
        AliasFactoryInterface $aliasFactory
    ) {
        parent::__construct();

        $this->aliasConfiguration = $aliasConfiguration;
        $this->aliasFactory = $aliasFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('alias:add')
            ->setDescription('Adds an alias to the configuration.')
            ->addArgument(
                'alias',
                InputArgument::REQUIRED,
                'The name of the alias.'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'The value of the alias.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aliasName = $input->getArgument('alias');
        $value = $input->getArgument('value');

        $alias = $this->aliasFactory->getAlias($aliasName, $value);

        $this->aliasConfiguration->addAlias($alias);
        $this->aliasConfiguration->save();

        $output->writeln(sprintf(
            '<info>Added the alias "%s" successfully.</info>',
            $alias->getName()
        ));
    }
}
