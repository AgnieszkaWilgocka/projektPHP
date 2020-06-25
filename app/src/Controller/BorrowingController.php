<?php

/**
 * Borrowing controller
 */
namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\User;
use App\Form\BorrowingType;
use App\Repository\BorrowingRepository;
use App\Repository\RecordRepository;
use App\Service\BorrowingService;
use App\Service\RecordService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class BorrowingController
 * @Route(
 *     "/borrowing"
 * )
 */
class BorrowingController extends AbstractController
{

    /**
     * @var BorrowingService
     */
    private $borrowingService;

    /**
     * @var RecordService
     */
    private $recordService;

    /**
     * BorrowingController constructor.
     *
     * @param BorrowingService $borrowingService
     * @param RecordService    $recordService
     */
    public function __construct(BorrowingService $borrowingService, RecordService $recordService)
    {
        $this->borrowingService = $borrowingService;
        $this->recordService = $recordService;
    }

    /**
     * /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route(
     *     "/manage",
     *     name="manage_borrowing",
     *     methods={"GET", "POST"},
     *
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function manageBorrowing(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->borrowingService->createdPaginatedList($page);

        return $this->render(
            'borrowing/manage.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     *
     * @Route(
     *     "/{id}/my_borrowing",
     *     methods={"GET", "POST"},
     *     name="my_borrowing",
     *     requirements={"id" : "[1-9]\d*"}
     * )
     */
    public function myBorrowing(Request $request, User $user)
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->borrowingService->createdPaginatedListByAuthor($page, $user);

        return $this->render(
            'borrowing/own.html.twig',
            ['pagination' => $pagination]
        );
    }



    /**
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return Response
     *
     * @Route(
     *     "/create",
     *     methods={"GET","POST"},
     *     name="borrowing_create",
     * )
     * @IsGranted("ROLE_USER")
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()-1);
            $borrowing->setAuthor($this->getUser());
            $borrowing->setCreatedAt(new \DateTime());
            $borrowing->setIsExecuted(false);
            $this->recordService->save($record);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'your borrowing has sent');


            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * @param Request   $request
     * @param Borrowing $borrowing
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/accept",
     *     methods={"GET", "PUT"},
     *     name="borrowing_accept",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function accept(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(FormType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'order has been accepted');

            return $this->redirectToRoute('manage_borrowing');
        }

        return $this->render(
            'borrowing/accept.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * @param Request   $request
     * @param Borrowing $borrowing
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/decline",
     *     methods={"GET", "PUT"},
     *     name="borrowing_decline",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function decline(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $this->recordService->save($record);
            $this->borrowingService->delete($borrowing);

            $this->addFlash('success', 'borrowing has been declined');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'borrowing/decline.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * @param Request   $request
     * @param Borrowing $borrowing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/return",
     *     methods={"GET", "DELETE"},
     *     name="borrowing_return",
     *     requirements={"id" : "[1-9]\d*"}
     * )
     */
    public function returnBorrowing(Request $request, Borrowing $borrowing)
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isValid()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $this->recordService->save($record);
            $this->borrowingService->delete($borrowing);

            $this->addFlash('success', 'your borrowing has been returned');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'borrowing/return.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }
}
