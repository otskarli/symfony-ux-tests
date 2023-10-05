<?php

namespace App\Components;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;

#[AsLiveComponent]
class PostCreate extends AbstractController
{
    use DefaultActionTrait;

    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?Post $blogPost = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PostType::class, $this->blogPost);
    }

    #[LiveAction]
    public function addComponent(): void
    {
        $this->formValues['components'][] = [];
    }

    #[LiveAction]
    public function removeComponent(#[LiveArg] int $index): void
    {
        unset($this->formValues['components'][$index]);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): RedirectResponse
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var Post $post */
        $post = $this->getForm()->getData();

        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post saved!');

        return $this->redirectToRoute('homepage');
    }
}
