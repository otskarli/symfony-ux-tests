<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'post')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostComponent::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
    }

    public function getComponents (): Collection
    {
        return $this->components->filter(function ($component) { return $component instanceof PostTextComponent; });
    }

    public function addComponent (PostComponent $component): static
    {
        if ($component->getDiscriminatorType() == "text") {
            $this->components->add(
                (new PostTextComponent())
                    ->setTitle($component->getTitle())
                    ->setContent($component->getContent())
            );
        }

        if ($component->getDiscriminatorType() == "image") {
            $this->components->add(
                (new PostImageComponent())
                    ->setTitle($component->getTitle())
                    ->setContent($component->getContent())
            );
        }

        return $this;
    }

    public function removeComponent (PostComponent $component): static
    {
        $this->components->removeElement($component);
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Post
    {
        $this->title = $title;
        return $this;
    }
}