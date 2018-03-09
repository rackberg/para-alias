<?php

namespace ParaAlias\Entity;

/**
 * Interface AliasInterface.
 *
 * @package ParaAlias\Entity
 */
interface AliasInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the value.
     *
     * @return string
     */
    public function getValue(): string;
}
