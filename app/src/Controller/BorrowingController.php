<?php

/**
 * Borrowing controller
 */
namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\Record;
use App\Entity\User;
use App\Form\BorrowingType;
use App\Service\BorrowingService;
use App\Service\RecordService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
     * Manage borrowing action
     *
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
     *  My borrowing action
     *
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
     * @IsGranted(
     *     "USER_VIEW",
     *     subject="user",
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
     * Create borrowing
     *
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
//            if ($record->getAmount() == 0) {
//                $this->addFlash('warning', 'sorry, but this record is not available');
//
//                return $this->redirectToRoute('borrowing_create');
//            }
            $record->setAmount($record->getAmount()-1);
            $borrowing->setAuthor($this->getUser());
            $borrowing->setCreatedAt(new \DateTime());
            $borrowing->setIsExecuted(false);
            $this->recordService->save($record);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'message_borrowing_send');

            return $this->redirectToRoute('record_index');
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
     * Accept borrowing function
     *
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function accept(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(FormType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'message_borrowing_accept');

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
     * Decline borrowing
     *
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
     *     methods={"GET", "DELETE"},
     *     name="borrowing_decline",
     *     requirements={"id": "[1-9]\d*"}
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function decline(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $this->recordService->save($record);
            $this->borrowingService->delete($borrowing);

            $this->addFlash('success', 'message_borrowing_decline');

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
     * Return borrowing
     *
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
     * @IsGranted(
     *     "BORROWING_DELETE",
     *     subject="borrowing",
     * )
     */
    public function returnBorrowing(Request $request, Borrowing $borrowing)
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $record = $form->get('record')->getData();
            $record->setAmount($record->getAmount()+1);
            $this->recordService->save($record);
            $this->borrowingService->delete($borrowing);

            $this->addFlash('success', 'message_borrowing_return');

            return $this->redirectToRoute('record_index');
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
