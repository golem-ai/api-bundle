<?php

namespace GolemAi\ApiBundle\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsonSerializerEncoderPass implements CompilerPassInterface
{
    const SERVICE_ID = 'serializer.encoder.json';

    public function process(ContainerBuilder $container)
    {
        if ($container->has(self::SERVICE_ID)) {
            return;
        }

        $definition = new Definition(JsonEncoder::class);
        $container->setDefinition(self::SERVICE_ID, $definition);
    }
}