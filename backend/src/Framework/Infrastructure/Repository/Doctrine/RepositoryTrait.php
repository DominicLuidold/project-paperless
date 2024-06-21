<?php

declare(strict_types=1);

namespace Framework\Infrastructure\Repository\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Fusonic\DDDExtensions\Domain\Model\EntityInterface;

trait RepositoryTrait
{
    private readonly EntityManagerInterface $entityManager;

    public function saveEntity(EntityInterface $entity): void
    {
        $this->entityManager->persist($entity);
    }
}
