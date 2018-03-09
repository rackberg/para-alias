<?php

namespace ParaAlias\Factory;

use ParaAlias\Entity\AliasInterface;

/**
 * Interface AliasFactoryInterface.
 *
 * @package ParaAlias\Factory
 */
interface AliasFactoryInterface
{
    /**
     * Creates and returns a new alias instance.
     *
     * @param string $name The name of the alias.
     * @param string $value The value.
     *
     * @return AliasInterface The alias instance.
     */
    public function getAlias(string $name, string $value = ''): AliasInterface;
}
