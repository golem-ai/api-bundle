<?php

namespace GolemAi\ApiBundle\DependencyInjection\Pass;

use GolemAi\Core\Serializer\Denormalizer\InteractionsDenormalizer;
use GolemAi\Core\Serializer\Denormalizer\PropertyHandler\PropertyHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InteractionsDenormalizerPass implements CompilerPassInterface
{
    const TAG = 'app.interactions.denormalizer.property';
    const SERVICE = InteractionsDenormalizer::class;

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

            if (!$reflection->implementsInterface(PropertyHandlerInterface::class)) {
                throw new \InvalidArgumentException(
                    sprintf('Object %s does not implement %s interface.', get_class($id), PropertyHandlerInterface::class)
                );
            }

            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addHandler', array(new Reference($id)));
        }
    }
}