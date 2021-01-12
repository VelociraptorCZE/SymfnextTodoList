<?php

namespace App\Repository;

use App\Entity\Todo;
use App\Transformer\TodoTransformer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    private TodoTransformer $todoTransformer;

    public function __construct(ManagerRegistry $registry, TodoTransformer $todoTransformer)
    {
        parent::__construct($registry, Todo::class);
        $this->todoTransformer = $todoTransformer;
    }

    public function findAllAsArray(): array
    {
        return array_map(fn (Todo $todo): array => $this->todoTransformer->transform($todo), $this->findAll());
    }
}
