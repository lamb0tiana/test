<?php

namespace App\Entity;

use App\Repository\AnwserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnwserRepository::class)]
class Anwser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'anwsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Field $field = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $answeredAt = null;

    #[ORM\Column(length: 10)]
    private ?string $identifier = null;

    public function __construct()
    {
        $this->answeredAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = trim($value) !== '' ? $value : '-';

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeImmutable
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(\DateTimeImmutable $answeredAt): static
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): static
    {
        $this->identifier = substr($identifier, 0, 10);

        return $this;
    }
}
