<?php

namespace ParaAlias\Tests\Unit\Configuration;

use org\bovigo\vfs\vfsStream;
use Para\Dumper\DumperInterface;
use Para\Parser\ParserInterface;
use Para\Service\ConfigurationManagerInterface;
use ParaAlias\Configuration\AliasConfiguration;
use ParaAlias\Configuration\AliasConfigurationInterface;
use ParaAlias\Entity\Alias;
use ParaAlias\Entity\AliasInterface;
use ParaAlias\Factory\AliasFactoryInterface;
use phpmock\prophecy\PHPProphet;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * Class AliasConfigurationTest.
 *
 * @package ParaAlias\Tests\Unit\Configuration
 */
class AliasConfigurationTest extends TestCase
{
    /**
     * The alias configuration to test.
     *
     * @var \ParaAlias\Configuration\AliasConfigurationInterface
     */
    private $aliasConfiguration;

    /**
     * The parser mock object.
     *
     * @var ParserInterface
     */
    private $parser;

    /**
     * The dumper mock object.
     *
     * @var DumperInterface
     */
    private $dumper;

    /**
     * The alias factory mock object.
     *
     * @var AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->parser = $this->prophesize(ParserInterface::class);
        $this->dumper = $this->prophesize(DumperInterface::class);
        $this->aliasFactory = $this->prophesize(AliasFactoryInterface::class);

        $this->aliasConfiguration = new AliasConfiguration(
            $this->parser->reveal(),
            $this->dumper->reveal(),
            $this->aliasFactory->reveal(),
            'the/path/to/the/config/file.yml'
        );
    }

    /**
     * Tests that a new alias will be added.
     */
    public function testAddingANewAlias()
    {
        $alias = $this->prophesize(AliasInterface::class);
        $alias
            ->getName()
            ->willReturn('ls');

        $this->aliasConfiguration->addAlias($alias->reveal());

        $alias->getName()->shouldHaveBeenCalled();
    }

    /**
     * Tests that the getAlias() method returns the alias with the same name.
     */
    public function testTheMethodGetAliasReturnsTheConfiguredAlias()
    {
        $name = 'test';
        $this->aliasConfiguration->addAlias(new Alias($name, 'value'));

        $alias = $this->aliasConfiguration->getAlias($name);

        $this->assertEquals($name, $alias->getName());
    }

    /**
     * Tests that it is possible to remove an alias.
     */
    public function testRemovingAnAlias()
    {
        $name = 'test';

        // Add a test alias.
        $this->aliasConfiguration->addAlias(new Alias($name, 'value'));
        $this->aliasConfiguration->removeAlias($name);

        $alias = $this->aliasConfiguration->getAlias($name);

        $this->assertNull($alias);
    }

    /**
     * Tests that the getAliases() method return all added aliases.
     */
    public function testTheGetAliasesMethodReturnsAllAddedAliases()
    {
        $this->aliasConfiguration->addAlias(new Alias('alias1', 'foo'));
        $this->aliasConfiguration->addAlias(new Alias('alias2', 'bar'));
        $this->aliasConfiguration->addAlias(new Alias('alias3', 'baz'));

        $aliases = $this->aliasConfiguration->getAliases();

        $this->assertArrayHasKey('alias1', $aliases);
        $this->assertArrayHasKey('alias2', $aliases);
        $this->assertArrayHasKey('alias3', $aliases);
    }

    /**
     * Tests that the load method loads all configured aliases.
     */
    public function testTheLoadMethodLoadsAllConfiguredAliases()
    {
        $configContent = <<< EOF
default:
    project:
        path: "the/path/to/the/project"

aliases:
    ls: "ls -la"
EOF;

        vfsStream::setup('root', null, [
            'config' => [
                'para.yml' => $configContent,
            ],
        ]);

        $this->parser
            ->parse($configContent)
            ->willReturn([
                'default' => [
                    'project' => [
                        'path' => 'the/path/to/the/project',
                    ],
                ],
                'aliases' => [
                    'ls' => 'ls -la',
                ],
            ]);

        $alias = new Alias('ls', 'ls -la');

        $this->aliasFactory
            ->getAlias('ls', 'ls -la')
            ->willReturn($alias);

        $this->aliasConfiguration->load(vfsStream::url('root/config/para.yml'));

        $result = $this->aliasConfiguration->getAliases();

        $this->arrayHasKey('ls', $result);
        $this->assertEquals($alias, $result['ls']);
    }

    public function testTheSaveMethodSavesTheStoredAliasesIntoTheConfiguration()
    {
        $configContent = <<< EOF
default:
    project:
        path: "the/path/to/the/project"
EOF;

        $fileSystem = vfsStream::setup('root', null, [
            'config' => [
                'para.yml' => $configContent,
            ],
        ]);

        $this->parser
            ->parse($configContent)
            ->willReturn([
                'default' => [
                    'project' => [
                        'path' => 'the/path/to/the/project',
                    ],
                ],
            ]);

        $this->aliasConfiguration->load(vfsStream::url('root/config/para.yml'));

        $this->aliasConfiguration->addAlias(new Alias('ls', 'ls -la'));

        $expectedContent = <<< EOF
default:
    project:
        path: "the/path/to/the/project"
aliases:
    ls: "ls -la"
EOF;

        $this->dumper
            ->dump(Argument::type('array'))
            ->willReturn($expectedContent);

        $result = $this->aliasConfiguration->save(vfsStream::url('root/config/para.yml'));

        $this->assertTrue($result);
        $this->assertEquals($expectedContent, $fileSystem->getChild('config/para.yml')->getContent());
    }
}
