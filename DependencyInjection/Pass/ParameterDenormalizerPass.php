<?php

namespace GolemAi\ApiBundle\DependencyInjection\Pass;

use GolemAi\Core\Extractor\ParametersDataExtractorInterface;
use GolemAi\Core\Serializer\Denormalizer\ParameterDenormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ParameterDenormalizerPass implements CompilerPassInterface
{
    const TAG = 'golem.parameter_extractor';
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
            $taggedService = $container->findDefinition($id);
            $reflection = new \ReflectionClass($taggedService->getClass());

            if (!$reflection->implementsInterface(ParametersDataExtractorInterface::class)) {
                throw new \InvalidArgumentException(
                    sprintf('Object %s does not implement %s interface.', get_class($id), ParametersDataExtractorInterface::class)
                );
            }

            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addExtractor', array($taggedService));
        }
    }
}