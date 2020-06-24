<?php

/**
 * Borrowing controller
 */
namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\BorrowingStatus;
use App\Form\BorrowingType;
use App\Repository\BorrowingRepository;
use App\Repository\BorrowingStatusRepository;
use App\Repository\RecordRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BorrowingController
 * @Route(
 *     "/borrowing"
 * )
 */
class BorrowingController extends AbstractController
{
    /**
     * @param Request $request
     * @param BorrowingRepository $borrowingRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route(
     *     "/manage",
     *     name="manage_borrowings",
     *     methods={"GET", "POST"},
     * )
     */
    public function manageBorrowing(Request $request, BorrowingRepository $borrowingRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $paginator->paginate(
            $borrowingRepository->queryAll(),
            $page,
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'borrowing/manage.html.twig',
            ['pagination' => $pagination]
        );
    }



    /**
     * @param \Symfony\Component\HttpFoundation\Request $request             HTTP Request
     * @param \App\Repository\BorrowingRepository       $borrowingRepository Borrowing repository
     * @param \App\Entity\Record                        $record              Record entity
     * @param \App\Repository\RecordRepository          $recordRepository    Record repository
     *
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
    public function create(Request $request, BorrowingRepository $borrowingRepository, RecordRepository $recordRepository): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()-1);
            $borrowing->setCreatedAt(new \DateTime());
            $borrowing->setIsExecuted(false);
            $recordRepository->save($record);
            $borrowingRepository->save($borrowing);

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
     * @param Request $request
     * @param Borrowing $borrowing
     * @param BorrowingRepository $borrowingRepository
     *
     * @return Response
     *
     * @Route(
     *     "/{id}/accept",
     *     methods={"GET", "PUT"},
     *     name="borrowing_accept",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function accept(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $borrowingRepository->save($borrowing);

            $this->addFlash('success', 'order has been accepted');

            return $this->redirectToRoute('manage_borrowings');
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
     * @param Request $request
     * @param Borrowing $borrowing
     * @param BorrowingRepository $borrowingRepository
     * @param RecordRepository $recordRepository
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
    public function decline(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository, RecordRepository $recordRepository): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $borrowingRepository->save($borrowing);
            $recordRepository->save($record);

            $this->addFlash('success', 'borrowing has been declined');

            return $this->redirectToRoute('manage_borrowings');
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
     * @param Request $request
     * @param Borrowing $borrowing
     * @param BorrowingRepository $borrowingRepository
     * @param RecordRepository $recordRepository
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
    public function returnBorrowing(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository, RecordRepository $recordRepository)
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isValid()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $recordRepository->save($record);
            $borrowingRepository->delete($borrowing);

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
