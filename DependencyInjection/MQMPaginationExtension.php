<?php

namespace MQM\Bundle\PaginationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MQMPaginationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('orm.xml');
        
        // set user variables in the container
        $bundleNS = 'mqm_pagination';
        if (isset($config['limit_per_page']) && $config['limit_per_page'] != null) {
            $container->setParameter($bundleNS . '.limit_per_page', $config['limit_per_page']);
        }
        
        if (isset($config['namespace']) && $config['namespace'] != null) {
            $container->setParameter($bundleNS . '.request.namespace', $config['namespace']);
        }
        
        if (isset($config['range']) && $config['range'] != null) {
            $container->setParameter($bundleNS . '.web_pagination_range', $config['range']);
        }
        
        if (isset($config['pagination'])) {
            $paginationClass = $config['pagination']['class'];
            $container->setParameter($bundleNS . '.web_pagination.class', $paginationClass);
        }
        
        if (isset($config['page_factory'])) {
            $pageFactoryClass = $config['page_factory']['class'];
            $container->setParameter($bundleNS . '.web_page_factory.class', $pageFactoryClass);
        }
    }
}
