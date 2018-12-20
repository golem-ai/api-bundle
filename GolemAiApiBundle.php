<?php

namespace GolemAi\ApiBundle;

use GolemAi\ApiBundle\DependencyInjection\Pass\GolemSerializerPass;
use GolemAi\ApiBundle\DependencyInjection\Pass\InteractionsDenormalizerPass;
use GolemAi\ApiBundle\DependencyInjection\Pass\JsonSerializerEncoderPass;
use GolemAi\ApiBundle\DependencyInjection\Pass\ParameterDenormalizerPass;
use GolemAi\Core\Serializer\Denormalizer\PropertyHandler\DenormalizerPropertyHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GolemAiApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new InteractionsDenormalizerPass());
        $container->registerForAutoconfiguration(DenormalizerPropertyHandlerInterface::class)
            ->addTag(InteractionsDenormalizerPass::TAG)
        ;

        $container->addCompilerPass(new ParameterDenormalizerPass());
        $container->registerForAutoconfiguration(ParameterDenormalizerPass::class)
            ->addTag(ParameterDenormalizerPass::TAG)
        ;

        $container->addCompilerPass(new GolemSerializerPass());
        $container->addCompilerPass(new JsonSerializerEncoderPass());
    }
}