<?php

namespace GolemAi\ApiBundle\DependencyInjection\Pass;

use GolemAi\Core\Extractor\ParametersDataExtractorInterface;
use GolemAi\Core\Serializer\Denormalizer\ParameterDenormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ParameterDenormalizerPass implements CompilerPassInterface
{
    const TAG = 'app.parameter.denormalizer.property';
    const SERVICE = ParameterDenormalizer::class;

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::SERVICE)) {
            return;
        }

        $definition = $container->findDefinition(self::SERVICE);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds(self::TAG);

        foreach ($taggedServices as $id => $tags) {
            $reflection = new \ReflectionClass($id);

            if (!$reflection->implementsInterface(ParametersDataExtractorInterface::class)) {
                throw new \InvalidArgumentException(
                    sprintf('Object %s does not implement %s interface.', get_class($id), ParametersDataExtractorInterface::class)
                );
            }

            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addExtractor', array(new Reference($id)));
        }
    }
}