<?php

namespace ParaAlias\Tests\Unit\Command;

use Para\Factory\TableOutputFactoryInterface;
use ParaAlias\Command\ListAliasesCommand;
use ParaAlias\Configuration\AliasConfigurationInterface;
use ParaAlias\Entity\AliasInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ListAliasesCommandTest
 *
 * @package ParaAlias\Tests\Unit\Command
 */
class ListAliasesCommandTest extends TestCase
{
    /**
     * The application.
     *
     * @var \Symfony\Component\Console\Application
     */
    private $application;

    /**
     * The alias configuration.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * The table output factory.
     *
     * @var \Para\Factory\TableOutputFactoryInterface
     */
    private $tableOutputFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->aliasConfiguration = $this->prophesize(AliasConfigurationInterface::class);
        $this->tableOutputFactory = $this->prophesize(TableOutputFactoryInterface::class);

        $this->application = new Application();
        $this->application->add(new ListAliasesCommand(
            $this->aliasConfiguration->reveal(),
            $this->tableOutputFactory->reveal()
        ));
    }

    public function testTheExecuteMethodReturnsTheCorrrectOutputShowingAListOfAliases()
    {
        $command = $this->application->find('aliases:list');
        $parameters = [
            'command' => $command->getName(),
        ];

        $alias = $this->prophesize(AliasInterface::class);
        $alias->getName()->shouldBeCalled();
        $alias->getValue()->shouldBeCalled();

        $this->aliasConfiguration->getAliases()->shouldBeCalled();
        $this->aliasConfiguration
            ->getAliases()
            ->willReturn([
                $alias->reveal(),
            ]);

        $table = $this->prophesize(Table::class);
        $table->setHeaders(['Alias', 'Value'])->shouldBeCalled();
        $table->setRows(Argument::type('array'))->shouldBeCalled();
        $table->render()->shouldBeCalled();

        $this->tableOutputFactory
            ->getTable(Argument::type(OutputInterface::class))
            ->shouldBeCalled();
        $this->tableOutputFactory
            ->getTable(Argument::type(OutputInterface::class))
            ->willReturn($table->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);
    }
}
