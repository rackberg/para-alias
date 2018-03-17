<?php

namespace ParaAlias\Tests\Unit\Command;

use ParaAlias\Command\RemoveAliasCommand;
use ParaAlias\Configuration\AliasConfigurationInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class RemoveAliasCommandTest
 *
 * @package ParaAlias\Tests\Unit\Command
 */
class RemoveAliasCommandTest extends TestCase
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->aliasConfiguration = $this->prophesize(AliasConfigurationInterface::class);

        $this->application = new Application();
        $this->application->add(new RemoveAliasCommand(
            $this->aliasConfiguration->reveal()
        ));
    }

    /**
     * Tests that the execute() method returns the correct output when the alias has been deleted.
     */
    public function testTheExecuteMethodReturnsTheCorrectOutputWhenTheAliasHasBeenDeleted()
    {
        $command = $this->application->find('alias:remove');
        $parameters = [
            'command' => $command->getName(),
            'name' => 'ls',
        ];

        $this->aliasConfiguration->removeAlias('ls')->shouldBeCalled();

        $this->aliasConfiguration->save()->shouldBeCalled();

        $commandTester = new CommandTester($command);
        $commandTester->execute($parameters);

        $output = $commandTester->getDisplay();

        $this->assertContains('The alias "ls" has been removed successfully.', $output);
    }
}
