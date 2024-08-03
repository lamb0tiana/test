<?php

namespace App\Entity;

use App\Repository\FieldAttributesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldAttributesRepository::class)]
class FieldAttributes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $required = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $options = null;

    #[ORM\Column(nullable: true)]
    private ?bool $expanded = null;

    #[ORM\OneToOne(inversedBy: 'fieldAttributes', cascade: ['persist', 'remove'])]
    private ?Field $Field = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(?bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getExpanded(): ?bool
    {
        return $this->expanded;
    }

    public function setExpanded(?bool $isExpanded): static
    {
        $this->expanded = $isExpanded;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->Field;
    }

    public function setField(?Field $Field): static
    {
        $this->Field = $Field;

        return $this;
    }
}
