<?php

/**
 * Tag controller
 */
namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController
 * @Route(
 *     "/tag"
 * )
 */
class TagController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP Request
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator     Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     name="tag_index"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $paginator->paginate(
            $tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEM_PER_PAGE
        );

        return $this->render(
            'tag/index.html.twig',
            [
                'pagination' => $pagination
            ]
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP Request
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="tag_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, TagRepository $tagRepository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);

            $this->addFlash('success', 'Tag has been created successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP Request
     * @param \App\Entity\Tag                           $tag           Tag entity
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     name="tag_edit",
     *     requirements={"id" : "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);

            $this->addFlash('success', 'tag_updated_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP Request
     * @param \App\Entity\Tag                           $tag           Entity tag
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "{id}/delete",
     *     methods={"GET", "DELETE"},
     *     name="tag_delete",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->delete($tag);

            $this->addFlash('success', 'tag_deleted_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}
