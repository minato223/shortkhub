<?php

namespace App\AppGenerator\Domain\Entity;

use App\AppGenerator\Domain\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project {

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $description = null;

    #[ORM\OneToOne(targetEntity: IconDocument::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotBlank()]
    private ?IconDocument $icon = null;

    public ?string $iconPath = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Url()]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?string $uuid = null;

    /**
     * @var Collection<int, AppVersion>
     */
    #[ORM\OneToMany(targetEntity: AppVersion::class, mappedBy: 'project')]
    private Collection $versions;

    public function __construct() {
        $this->versions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIcon(): ?IconDocument
    {
        return $this->icon;
    }

    public function setIcon(?IconDocument $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(AppVersion $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions[] = $version;
            $version->setProject($this);
        }

        return $this;
    }

    public function removeVersion(AppVersion $version): self
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getProject() === $this) {
                $version->setProject(null);
            }
        }

        return $this;
    }

}