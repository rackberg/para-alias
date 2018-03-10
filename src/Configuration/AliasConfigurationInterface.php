<?php

namespace ParaAlias\Configuration;

use Para\Configuration\ConfigurationInterface;
use ParaAlias\Entity\Alias;
use ParaAlias\Entity\AliasInterface;

/**
 * Interface AliasConfigurationInterface.
 *
 * @package ParaAlias\Configuration
 */
interface AliasConfigurationInterface extends ConfigurationInterface
{
    /**
     * Adds a new alias.
     *
     * @param AliasInterface $alias The alias to add.
     */
    public function addAlias(AliasInterface $alias): void;

    /**
     * Removes an alias from the configuration.
     *
     * @param string $aliasName The name of the alias to remove.
     */
    public function removeAlias(string $aliasName): void;

    /**
     * Returns an alias.
     *
     * @param string $name The name of the alias.
     *
     * @return null|Alias The alias instance or null.
     */
    public function getAlias(string $name): ?Alias;

    /**
     * Returns all aliases.
     *
     * @return AliasInterface[] The aliases
     */
    public function getAliases(): array;
}
