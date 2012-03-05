<?php

namespace Whitewashing\Bundle\LogglyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('whitewashing_loggly');

        $rootNode->children()
                ->scalarNode('key')->isRequired()->end()
                ->scalarNode('port')->defaultValue(443)->end()
                ->scalarNode('host')->defaultValue('logs.loggly.com')->end()
                ->scalarNode('level')->defaultValue(constant('Monolog\Logger::DEBUG'))->end()
                ->booleanNode('bubble')->defaultValue(true)->end()
            ->end();

        return $treeBuilder;
    }
}
