<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\OperationLimitBundle\DependencyInjection;

use DawBed\ComponentBundle\Configuration\Entity;
use DawBed\PHPOperationLimit\OperationLimit;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const NODE_TAGS = 'tags';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(OperationLimitExtension::ALIAS);

        $entity = new Entity($rootNode);
        $entity->new('operationLimit', OperationLimit::class)
            ->end();

        return $treeBuilder;
    }
}