<?php

namespace ParaAlias\Entity;

/**
 * Class Alias.
 *
 * @package ParaAlias\Entity
 */
class Alias implements AliasInterface
{
    /**
     * The alias name.
     *
     * @var string
     */
    private $name;

    /**
     * The value.
     *
     * @var string
     */
    private $value;

    /**
     * Alias constructor.
     *
     * @param string $name The alias name.
     * @param string $value The value.
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
