<?php

/**
 * Tag controller
 */
namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Service\TagService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
     * Tag service
     *
     * @var TagService
     */
    private $tagService;

    /**
     * TagController constructor.
     *
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Index action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     name="tag_index"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->tagService->createPaginatedList($page);

        return $this->render(
            'tag/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Tag searching
     *
     * @param Tag $tag
     *
     * @return Response
     *
     * @Route(
     *      "/{id}",
     *     methods={"GET"},
     *     name="tag_searching",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function tagSearching(Tag $tag): Response
    {
        return $this->render(
            'tag/show.html.twig',
            ['tag' => $tag]
        );
    }


    /**
     * Create tag
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
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
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash('success', 'message_create');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * Edit tag
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \App\Entity\Tag                           $tag     Tag entity
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
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash('success', 'message_update');

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
     * Delete tag
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \App\Entity\Tag                           $tag     Entity tag
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
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);

            $this->addFlash('success', 'message_delete');

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
