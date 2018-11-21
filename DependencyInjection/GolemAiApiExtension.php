<?php

namespace GolemAi\ApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class GolemAiApiExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/services'));
        $this->loadCore($configs, $container, $loader);
    }

    private function loadSerializer(array $configs, ContainerBuilder $container, FileLoader $loader)
    {
        $loader->load('serializer.yaml');
    }

    private function loadCore(array $configs, ContainerBuilder $containerBuilder, FileLoader $loader) {
        if (!is_dir(__DIR__.'/../../php-core')) {
            return;
        }

        $this->loadSerializer($configs, $containerBuilder, $loader);
    }
}