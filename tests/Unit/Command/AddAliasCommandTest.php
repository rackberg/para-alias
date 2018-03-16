<?php

namespace ParaAlias\Tests\Unit\Command;

use ParaAlias\Command\AddAliasCommand;
use ParaAlias\Configuration\AliasConfigurationInterface;
use ParaAlias\Entity\AliasInterface;
use ParaAlias\Factory\AliasFactoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class AddAliasCommandTest
 *
 * @package ParaAlias\Tests\Unit\Command
 */
class AddAliasCommandTest extends TestCase
{
    /**
     * The application.
     *
     * @var \Symfony\Component\Console\Application
     */
    private $application;

    /**
     * The alias configuration mock object.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * The alias factory mock object.
     *
     * @var \ParaAlias\Factory\AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->aliasConfiguration = $this->prophesize(AliasConfigurationInterface::class);
        $this->aliasFactory = $this->prophesize(AliasFactoryInterface::class);

        $this->application = new Application();
        $this->application->add(new AddAliasCommand(
            $this->aliasConfiguration->reveal(),
            $this->aliasFactory->reveal()
        ));
    }

    /**
     * Tests that the execute() method returns the correct output when adding a plugin is successful.
     */
    public function testTheExecuteMethodReturnsTheCorrectOutputWhenAddingAPluginIsSuccessful()
    {
        $command = $this->application->find('alias:add');
        $parameters = [
            'command' => $command->getName(),
            'alias' => 'ls',
            'value' => 'ls -la',
        ];

        $alias = $this->prophesize(AliasInterface::class);
        $alias->getName()->willReturn('ls');

        $this->aliasFactory
            ->getAlias('ls', 'ls -la')
            ->shouldBeCalled();
        $this->aliasFactory
            ->getAlias('ls', 'ls -la')
            ->willReturn($alias->reveal());

        $this->aliasConfiguration
            ->addAlias(Argument::type(AliasInterface::class))
            ->shouldBeCalled();

        $this->aliasConfiguration->save()->shouldBeCalled();

        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);

        $output = $commandTester->getDisplay();

        $this->assertContains('Added the alias "ls" successfully.', $output);
    }
}
