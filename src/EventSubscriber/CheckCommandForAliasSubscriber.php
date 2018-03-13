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
     * The path to the config file.
     *
     * @var string
     */
    private $configFile;

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
     * @param string $configFile The path to the config file.
     */
    public function __construct(
        AliasConfigurationInterface $aliasConfiguration,
        string $configFile
    ) {
        $this->aliasConfiguration = $aliasConfiguration;
        $this->configFile = $configFile;
    }

    /**
     * Replaces the command with an alias.
     *
     * @param BeforeShellCommandExecutionEvent $event The event.
     */
    public function replaceWithAlias(BeforeShellCommandExecutionEvent $event)
    {
        $this->aliasConfiguration->load($this->configFile);

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
