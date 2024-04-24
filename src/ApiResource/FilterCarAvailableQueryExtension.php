<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Car;
use Doctrine\ORM\QueryBuilder;

class FilterCarAvailableQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        /**
         * This query to retrieve the list of available cars
         */
        if (Car::class === $resourceClass) {
            $rootAliases = $queryBuilder->getRootAliases()[0];
            $queryBuilder->leftJoin($rootAliases . '.reservations', 'r')
                ->andWhere('r.id IS NULL OR r.endDate < :today')
                ->setParameter('today', new \DateTime());
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
    }
}
