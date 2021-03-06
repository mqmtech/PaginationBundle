<?php

namespace MQM\PaginationBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('mqm_pagination');

        $rootNode
            ->children()
                ->scalarNode('limit_per_page')->defaultValue(10)->end()
                ->scalarNode('namespace')->defaultValue('')->end()
                ->scalarNode('range')->defaultValue(3)->end()
                ->arrayNode('pagination')
                    ->children()
                    ->scalarNode('class')->defaultValue(null)->end()
                    ->end()
                ->end()
                ->arrayNode('pagination_factory')
                    ->children()
                    ->scalarNode('class')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
