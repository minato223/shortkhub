<?php

namespace App\AppGenerator\Domain\Entity;

use App\AppGenerator\Domain\Entity\Common\Document;
use App\AppGenerator\Domain\Repository\ApkDocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApkDocumentRepository::class)]
class ApkDocument extends Document
{
    public const TYPE = 'apk';
}