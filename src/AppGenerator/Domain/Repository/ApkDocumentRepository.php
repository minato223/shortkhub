<?php

namespace App\AppGenerator\Domain\Repository;

use App\AppGenerator\Domain\Entity\ApkDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApkDocument>
 */
class ApkDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApkDocument::class);
    }
}