<?php
/**
 * Category controller.
 */
namespace App\Controller;

use\App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     *
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\CategoryRepository $categoryRepository Category repository
     * @param \
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="category_index"
     * )
     */
    public function index(Request $request, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $page=$request->query->getInt('page', '1');
        $pagination= $paginator->paginate(
            $categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'category/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @param \App\Entity\Category $category Category entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="category_show",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function show(Category $category): Response
    {
        return $this->render(
            'category/show.html.twig',
            ['category' => $category]

        );
    }

}