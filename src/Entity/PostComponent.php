<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

#[ORM\Entity]
#[InheritanceType("SINGLE_TABLE")]
#[DiscriminatorColumn(name: "discriminator", type: "string")]
#[DiscriminatorMap([
    "default" => PostComponent::class,
    "text" => PostTextComponent::class,
    "image" => PostImageComponent::class
])]
class PostComponent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    private ?string $discriminatorType = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): PostComponent
    {
        $this->content = $content;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setDiscriminatorType(?string $discriminatorType): PostComponent
    {
        $this->discriminatorType = $discriminatorType;
        return $this;
    }

    public function getDiscriminatorType(): ?string
    {
        return $this->discriminatorType;
    }
}
