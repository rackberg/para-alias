<?php

namespace ParaAlias\Command;

use Para\Factory\TableOutputFactoryInterface;
use ParaAlias\Configuration\AliasConfigurationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListAliasesCommand
 *
 * @package ParaAlias\Command
 */
class ListAliasesCommand extends Command
{
    /**
     * The alias configuration.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * The table output factory.
     *
     * @var TableOutputFactoryInterface
     */
    private $tableOutputFactory;

    /**
     * ListAliasesCommand constructor.
     *
     * @param \ParaAlias\Configuration\AliasConfigurationInterface $aliasConfiguration The alias configuration.
     * @param \Para\Factory\TableOutputFactoryInterface $tableOutputFactory The table output factory.
     */
    public function __construct(
        AliasConfigurationInterface $aliasConfiguration,
        TableOutputFactoryInterface $tableOutputFactory
    ) {
        parent::__construct();

        $this->aliasConfiguration = $aliasConfiguration;
        $this->tableOutputFactory = $tableOutputFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('aliases:list')
            ->setDescription('Shows a list of all configured aliases.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aliases = $this->aliasConfiguration->getAliases();

        $rows = [];
        foreach ($aliases as $alias) {
            $rows[] = [
                'alias' => $alias->getName(),
                'value' => $alias->getValue(),
            ];
        }

        $table = $this->tableOutputFactory->getTable($output);
        $table->setHeaders(['Alias', 'Value']);
        $table->setRows($rows);
        $table->render();
    }
}
