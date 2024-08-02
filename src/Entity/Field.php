<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use App\TypeDefinition\FieldType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $label = null;

    #[ORM\Column(type: 'integer', length: 1, enumType: FieldType::class)]
    private ?int $type = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    #[ORM\OneToOne(mappedBy: 'Field', cascade: ['persist', 'remove'])]
    private ?FieldAttributes $fieldAttributes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function getFieldAttributes(): ?FieldAttributes
    {
        return $this->fieldAttributes;
    }

    public function setFieldAttributes(?FieldAttributes $fieldAttributes): static
    {
        // unset the owning side of the relation if necessary
        if ($fieldAttributes === null && $this->fieldAttributes !== null) {
            $this->fieldAttributes->setField(null);
        }

        // set the owning side of the relation if necessary
        if ($fieldAttributes !== null && $fieldAttributes->getField() !== $this) {
            $fieldAttributes->setField($this);
        }

        $this->fieldAttributes = $fieldAttributes;

        return $this;
    }
}
