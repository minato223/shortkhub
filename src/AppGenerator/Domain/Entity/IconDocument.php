<?php

namespace App\AppGenerator\Domain\Entity;

use App\AppGenerator\Domain\Entity\Common\Document;
use App\AppGenerator\Domain\Repository\IconDocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IconDocumentRepository::class)]
class IconDocument extends Document
{
    public const TYPE = 'icon';
}