<?php

namespace ParaAlias\Configuration;

use Para\Configuration\AbstractConfiguration;
use Para\Dumper\DumperInterface;
use Para\Parser\ParserInterface;
use ParaAlias\Entity\Alias;
use ParaAlias\Entity\AliasInterface;
use ParaAlias\Factory\AliasFactoryInterface;

/**
 * Class AliasConfiguration.
 *
 * @package ParaAlias\Configuration
 */
class AliasConfiguration extends AbstractConfiguration implements AliasConfigurationInterface
{
    /**
     * The aliases.
     *
     * @var \ParaAlias\Entity\AliasInterface[]
     */
    private $aliases = [];

    /**
     * The alias factory.
     *
     * @var AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * AliasConfiguration constructor.
     *
     * @param ParserInterface $parser The parser.
     * @param DumperInterface $dumper The dumper.
     * @param AliasFactoryInterface $aliasFactory The alias factory.
     * @param string $configFile The path to the config file.
     */
    public function __construct(
        ParserInterface $parser,
        DumperInterface $dumper,
        AliasFactoryInterface $aliasFactory,
        string $configFile
    ) {
        parent::__construct($parser, $dumper, $configFile);

        $this->aliasFactory = $aliasFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $fileName = null): void
    {
        parent::load($fileName);

        $this->aliases = [];

        if (isset($this->configuration['aliases'])) {
            foreach ($this->configuration['aliases'] as $name => $value) {
                $this->aliases[$name] = $this->aliasFactory->getAlias($name, $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $fileName = null): bool
    {
        unset($this->configuration['aliases']);

        foreach ($this->aliases as $alias) {
            $this->configuration['aliases'][$alias->getName()] = $alias->getValue();
        }

        return parent::save($fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function addAlias(AliasInterface $alias): void
    {
        $this->aliases[$alias->getName()] = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAlias(string $aliasName): void
    {
        unset($this->aliases[$aliasName]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(string $name): ?Alias
    {
        return isset($this->aliases[$name]) ? $this->aliases[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }
}
