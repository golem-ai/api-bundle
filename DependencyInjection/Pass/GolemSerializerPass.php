<?php

namespace GolemAi\ApiBundle\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GolemSerializerPass implements CompilerPassInterface
{
    const SERVICE = 'golem.serializer';
    const NORMALIZER_TAG = 'golem.serializer.normalizer';
    const ENCODER_TAG = 'golem.serializer.encoder';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::SERVICE)) {
            return;
        }

        $definition = $container->findDefinition(self::SERVICE);

        $this->addNormalizers($definition, $container);
        $this->addEncoders($definition, $container);
    }

    public function addNormalizers(Definition $definition, ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(self::NORMALIZER_TAG);

        $arguments = array();
        foreach ($taggedServices as $id => $tags) {
            $taggedService = $container->findDefinition($id);
            $reflection = new \ReflectionClass($taggedService->getClass());

            if ($reflection->implementsInterface(DenormalizerInterface::class)
                || $reflection->implementsInterface(NormalizerInterface::class)
            ) {
                $arguments[] = $taggedService;
                continue;
            }

            throw new \InvalidArgumentException(
                sprintf('Object %s does not implement %s or %s interface.',
                    $reflection->getName(), DenormalizerInterface::class, NormalizerInterface::class)
            );
        }

        $definition->setArgument(0, $arguments);
    }

    public function addEncoders(Definition $definition, ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(self::ENCODER_TAG);

        $arguments = array();
        foreach ($taggedServices as $id => $tags) {
            $taggedService = $container->findDefinition($id);
            $reflection = new \ReflectionClass($taggedService->getClass());

            if ($reflection->implementsInterface(DecoderInterface::class)
                || $reflection->implementsInterface(EncoderInterface::class)
            ) {
                $arguments[] = $taggedService;
                continue;
            }

            throw new \InvalidArgumentException(
                sprintf('Object %s does not implement %s or %s interface.',
                    $reflection->getName(), DecoderInterface::class, EncoderInterface::class)
            );
        }

        $definition->setArgument(1, $arguments);
    }
}