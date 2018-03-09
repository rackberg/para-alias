<?php

namespace ParaAlias\Tests\Unit\Factory;

use ParaAlias\Entity\AliasInterface;
use ParaAlias\Factory\AliasFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class AliasFactoryTest.
 *
 * @package ParaAlias\Tests\Unit\Factory
 */
class AliasFactoryTest extends TestCase
{
    /**
     * Tests that a new instance will be returned.
     */
    public function testThatANewInstanceWillBeReturned()
    {
        $aliasFactory = new AliasFactory();
        $result = $aliasFactory->getAlias('ls', 'ls -la');

        $this->assertTrue($result instanceof AliasInterface);
        $this->assertEquals('ls', $result->getName());
        $this->assertEquals('ls -la', $result->getValue());
    }
}
