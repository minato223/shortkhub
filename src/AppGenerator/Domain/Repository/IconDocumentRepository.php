<?php

namespace App\AppGenerator\Domain\Repository;

use App\AppGenerator\Domain\Entity\IconDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IconDocument>
 */
class IconDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IconDocument::class);
    }
}