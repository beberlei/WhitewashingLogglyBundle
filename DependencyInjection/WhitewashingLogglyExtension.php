<?php

namespace Whitewashing\Bundle\LogglyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WhitewashingLogglyExtension extends Extension
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

        // Set the level to the correct integer value provided by Monoglog
        $config['level'] = is_int($config['level']) ? $config['level'] : constant('Monolog\Logger::'.strtoupper($config['level']));

        $container->setParameter('whitewashing_loggly.loggly.key', $config['key']);
        $container->setParameter('whitewashing_loggly.loggly.host', $config['host']);
        $container->setParameter('whitewashing_loggly.loggly.port', $config['port']);
        $container->setParameter('whitewashing_loggly.loggly.level', $config['level']);
        $container->setParameter('whitewashing_loggly.loggly.bubble', $config['bubble']);
    }
}
