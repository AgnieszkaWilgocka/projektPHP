<?php

/**
 * Record Controller.
 */
namespace App\Controller;

use App\Entity\Record;
use App\Form\RecordType;
use App\Service\RecordService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RecordController
 *
 * @Route("/record")
 */

class RecordController extends AbstractController
{
    /**
     * @var RecordService
     */
    private $recordService;

    /**
     * RecordController constructor.
     *
     * @param RecordService $recordService
     */
    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *      methods={"GET"},
     *      name="record_index"
     *)
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->recordService->createPaginatedList($page);

        return $this->render(
            'record/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Record $record Record entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *
     *    "/{id}",
     *     methods={"GET"},
     *     name="record_show",
     *     requirements={"id": "[1-9]\d*"}
     *
     * )
     */
    public function show(Record $record): Response
    {
        return $this->render(
            'record/show.html.twig',
            ['record' => $record]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *      methods={"GET", "POST"},
     *     name="record_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request):Response
    {
        $record = new Record();
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->save($record);

            $this->addFlash('success', 'record_created_successfully');

            return $this->redirectToRoute('record_index');
        }


        return $this->render(
            'record/create.html.twig',
            [
                'form' => $form->createView(),
                'record' => $record,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request HTPP request
     * @param \App\Entity\Record                        $record  Record entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     name="record_edit",
     *     requirements={"id" : "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Record $record): Response
    {
        $form = $this->createForm(RecordType::class, $record, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->save($record);

            $this->addFlash('success', 'record_edited_successfully');

            return $this->redirectToRoute('record_index');
        }

        return $this->render(
            'record/edit.html.twig',
            [
                'form' => $form->createView(),
                'record' => $record,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Record                        $record  Record entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     name="record_delete",
     *     requirements={"id": "[1-9]\d*"},
     *
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Record $record): Response
    {
        $form = $this->createForm(RecordType::class, $record, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->delete($record);

            $this->addFlash('success', 'record_deleted_successfully');

            return $this->redirectToRoute('record_index');
        }

        return $this->render(
            'record/delete.html.twig',
            [
                'form' => $form->createView(),
                'record' => $record,
            ]
        );
    }
}
