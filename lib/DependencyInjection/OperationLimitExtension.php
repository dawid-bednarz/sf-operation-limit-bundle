<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\OperationLimitBundle\DependencyInjection;

use DawBed\OperationLimitBundle\Repository\OperationLimitRepository;
use DawBed\OperationLimitBundle\Service\EntityService;
use DawBed\OperationLimitBundle\Service\SupportTagService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;

class OperationLimitExtension extends Extension
{
    const ALIAS = 'dawbed_operation_limit_bundle';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $container->setParameter('bundle_dir', dirname(__DIR__));
        $configs = $this->processConfiguration($configuration, $configs);
        $loader = $this->prepareLoader($container);
        $loader->load('services.yaml');
        $this->prepareEntityService($configs['entities'], $container);
        $this->prepareOperationLimitRepository($configs['entities']['operationLimit'], $container);
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prepareLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/Resources/config'));
    }

    private function prepareOperationLimitRepository(string $entity, ContainerBuilder $container): void
    {
        $container->setDefinition(OperationLimitRepository::class, new Definition(OperationLimitRepository::class, [
            new Reference(ManagerRegistry::class),
            $entity
        ]));
    }

    private function prepareEntityService(array $entities, ContainerBuilder $container): void
    {
        $container->setDefinition(EntityService::class, new Definition(EntityService::class, [
            [
                'OperationLimit' => $entities['operationLimit'],
            ]
        ]));
    }

}