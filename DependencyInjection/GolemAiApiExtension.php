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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/services'));
        $this->loadCore($configs, $container, $loader);
    }

    private function loadCore(array $configs, ContainerBuilder $containerBuilder, FileLoader $loader) {
        if (!is_dir(__DIR__ . '/../../php-core')) {
            return;
        }

        $this->loadEntityFactories($loader);
        $this->loadSerializer($loader);
        $this->loadHttpClient($loader);
    }

    private function loadSerializer(FileLoader $loader)
    {
        $loader->load('encoders.yaml');
        $loader->load('normalizers.yaml');
        $loader->load('serializer.yaml');
    }

    private function loadHttpClient(FileLoader $loader)
    {
        $loader->load('http_client.yaml');
    }

    private function loadEntityFactories(FileLoader $loader)
    {
        $loader->load('factories.yaml');
    }
}