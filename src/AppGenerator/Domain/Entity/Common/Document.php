<?php

namespace App\AppGenerator\Domain\Entity\Common;

use App\AppGenerator\Domain\Entity\ApkDocument;
use App\AppGenerator\Domain\Entity\IconDocument;
use App\AppGenerator\Domain\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[
    ORM\Entity(repositoryClass: DocumentRepository::class),
    ORM\InheritanceType("JOINED"),
    ORM\DiscriminatorColumn("type", "string"),
    ORM\DiscriminatorMap([
        Document::TYPE => Document::class,
        ApkDocument::TYPE => ApkDocument::class,
        IconDocument::TYPE => IconDocument::class
    ]),
]
#[Vich\Uploadable]
class Document implements \Stringable
{

    const TYPE = 'document';

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'app_files', fileNameProperty: 'name', size: 'size')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __toString(): string
    {
        return $this->name ?? '';
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setSize(?int $size): void
    {
        $this->size = $size;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}