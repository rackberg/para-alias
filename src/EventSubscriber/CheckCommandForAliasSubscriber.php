<?php

namespace ParaAlias\EventSubscriber;

use Para\Event\BeforeShellCommandExecutionEvent;
use ParaAlias\Configuration\AliasConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CheckCommandForAliasSubscriber.
 *
 * @package ParaAlias\EventSubscriber
 */
class CheckCommandForAliasSubscriber implements EventSubscriberInterface
{
    /**
     * The alias configuration.
     *
     * @var AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            BeforeShellCommandExecutionEvent::NAME => [
                ['replaceWithAlias']
            ],
        ];
    }

    /**
     * CheckCommandForAliasSubscriber constructor.
     *
     * @param AliasConfigurationInterface $aliasConfiguration The alias configuration.
     */
    public function __construct(AliasConfigurationInterface $aliasConfiguration)
    {
        $this->aliasConfiguration = $aliasConfiguration;
    }

    /**
     * Replaces the command with an alias.
     *
     * @param BeforeShellCommandExecutionEvent $event The event.
     */
    public function replaceWithAlias(BeforeShellCommandExecutionEvent $event)
    {
        $command = $event->getCommand();
        $words = explode(' ', trim($command));

        foreach ($this->aliasConfiguration->getAliases() as $alias) {
            if ($command === $alias->getName()) {
                $command = $alias->getValue();
                break;
            }

            if ($words[0] === $alias->getName()) {
                $command = str_replace(':command', $command, $alias->getValue());
                break;
            }
        }

        $event->setCommand($command);
    }
}
