<?php

namespace ParaAlias\Factory;

use ParaAlias\Entity\Alias;
use ParaAlias\Entity\AliasInterface;

/**
 * Class AliasFactory.
 *
 * @package ParaAlias\Factory
 */
class AliasFactory implements AliasFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAlias(string $name, string $value = ''): AliasInterface
    {
        return new Alias($name, $value);
    }
}
