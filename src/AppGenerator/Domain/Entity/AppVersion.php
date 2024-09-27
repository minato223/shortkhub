<?php

namespace App\AppGenerator\Domain\Entity;

use App\AppGenerator\Domain\Repository\AppVersionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppVersionRepository::class)]
class AppVersion {

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $version = null;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $platform = null;

    #[ORM\OneToOne(targetEntity: ApkDocument::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ApkDocument $file = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'versions')]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getFile(): ?ApkDocument
    {
        return $this->file;
    }

    public function setFile(?ApkDocument $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}