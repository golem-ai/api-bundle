<?php

namespace GolemAi\ApiBundle;

use GolemAi\ApiBundle\DependencyInjection\Pass\InteractionsDenormalizerPass;
use GolemAi\ApiBundle\DependencyInjection\Pass\JsonSerializerEncoderPass;
use GolemAi\Core\Serializer\Denormalizer\PropertyHandler\PropertyHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GolemAiApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new InteractionsDenormalizerPass());
        $container->registerForAutoconfiguration(PropertyHandlerInterface::class)
            ->addTag(InteractionsDenormalizerPass::TAG)
        ;

        $container->addCompilerPass(new JsonSerializerEncoderPass());
    }
}