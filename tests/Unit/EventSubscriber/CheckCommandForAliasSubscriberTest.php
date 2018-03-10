<?php

namespace ParaAlias\Tests\Unit\EventSubscriber;

use Para\Event\BeforeShellCommandExecutionEvent;
use ParaAlias\Configuration\AliasConfigurationInterface;
use ParaAlias\Entity\Alias;
use ParaAlias\EventSubscriber\CheckCommandForAliasSubscriber;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * Class CheckCommandForAliasSubscriberTest.
 *
 * @package ParaAlias\Tests\Unit\EventSubscriber
 */
class CheckCommandForAliasSubscriberTest extends TestCase
{
    /**
     * The event subscriber to test.
     *
     * @var CheckCommandForAliasSubscriber
     */
    private $eventSubscriber;

    /**
     * The alias configuration mock object.
     *
     * @var AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->aliasConfiguration = $this->prophesize(AliasConfigurationInterface::class);

        $this->eventSubscriber = new CheckCommandForAliasSubscriber(
            $this->aliasConfiguration->reveal()
        );
    }
    
    /**
     * Tests that the replaceWithAlias() method detects that the command is exactly an alias.
     */
    public function testTheReplaceWithAliasMethodReplacesTheWholeCommandWithTheAliasValue()
    {
        $event = $this->prophesize(BeforeShellCommandExecutionEvent::class);
        $event
            ->getCommand()
            ->willReturn('ls');
        $event
            ->setCommand(Argument::type('string'))
            ->shouldBeCalled();

        $this->aliasConfiguration
            ->getAliases()
            ->willReturn([
                'ls' => new Alias('ls', 'ls -la'),
            ]);

        $this->eventSubscriber->replaceWithAlias($event->reveal());

        $this->aliasConfiguration->getAliases()->shouldBeCalled();
        $event->getCommand()->shouldHaveBeenCalledTimes(1);
        $event->setCommand('ls -la')->shouldHaveBeenCalled();
    }

    /**
     * Tests that the command will be replaced with an alias that has a parameter.
     */
    public function testReplacesWithAnAliasThatHasAnParameter()
    {
        $command = 'drush sql-sync @source @target -y';

        $event = $this->prophesize(BeforeShellCommandExecutionEvent::class);
        $event
            ->getCommand()
            ->willReturn($command);
        $event
            ->setCommand(Argument::type('string'))
            ->shouldBeCalled();

        $this->aliasConfiguration
            ->getAliases()
            ->willReturn([
                'drush' => new Alias('drush', 'docker-compose exec --user drupal drupal bash -c ":command"'),
            ]);

        $this->eventSubscriber->replaceWithAlias($event->reveal());

        $this->aliasConfiguration->getAliases()->shouldBeCalled();
        $event->getCommand()->shouldHaveBeenCalledTimes(1);
        $event
            ->setCommand('docker-compose exec --user drupal drupal bash -c "' . $command . '"')
            ->shouldHaveBeenCalled();
    }
}
