<?php

namespace App\Controller;

use App\Entity\Disposal;
use App\Form\DisposalType;
use App\Repository\DisposalRepository;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Shop;
use App\Entity\User;

/**
 * Disposal controller class
 */
class DisposalController extends Controller
{
    /**
     * Show index of all disposals available only to admin
     * @Route("/admin/disposal/", name="disposal_index", methods={"GET"})
     *
     * @param DisposalRepository $disposalRepository
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function index(DisposalRepository $disposalRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $disposalRepository->queryAll(),
            $request->query->getInt('page', 1),
            Disposal::NUMBER_OF_ITEMS
        );

        return $this->render('disposal/index.html.twig', [
            'disposals' => $pagination,
        ]);
    }

    /**
     * Show index of specific user disposals
     * @Route("/disposal/index/{userId}", name="disposal_user_index", requirements={"userId"="\d+"}, methods={"GET"})
     *
     * @param  int                $userId
     * @param  DisposalRepository $disposalRepository
     * @param  PaginatorInterface $paginator
     * @param  Request            $request
     *
     * @return Response
     */
    public function userDisposalIndex($userId, DisposalRepository $disposalRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $disposalRepository->queryForUser($userId),
            $request->query->getInt('page', 1),
            Disposal::NUMBER_OF_ITEMS
        );

        return $this->render('disposal/index.html.twig', [
            'disposals' => $pagination,
        ]);
    }

    /**
     * Show currently ordered disposal
     * @Route("/disposal/{id}/{user}", name="disposal_user_show", requirements={"id"="\d+", "user"="\d+"}, methods={"GET"})
     *
     * @param Disposal       $disposal
     * @param ShopRepository $shopRepository
     * @param string         $user
     *
     * @return Response
     */
    public function userShow(Disposal $disposal, ShopRepository $shopRepository, string $user): Response
    {
        $userId = $user;
        $user = $this->getUser();

        if (!$this->isGranted(USER::ROLE_ADMIN) && (int) $userId !== $user->getId()) {
            $this->addFlash('danger', 'message.you_cant_view_this_disposal');

            return $this->redirectToRoute('product_index');
        }

        $shopEntities = $shopRepository->findBy([], [], 1);

        return $this->render('disposal/user_show.html.twig', [
            'shop' => isset($shopEntities[0]) ? $shopEntities[0] : new Shop(),
            'disposal' => $disposal,
        ]);
    }

    /**
     * Show specific dispoal
     * @Route("/admin/disposal/{id}", name="disposal_show", methods={"GET"})
     *
     * @param Disposal $disposal
     *
     * @return Response
     */
    public function show(Disposal $disposal): Response
    {
        return $this->render('disposal/show.html.twig', [
            'disposal' => $disposal,
        ]);
    }

    /**
     * Edit disposal
     * @Route("/admin/disposal/{id}/edit", name="disposal_edit", methods={"GET","POST"})
     *
     * @param Request  $request
     * @param Disposal $disposal
     *
     * @return Response
     */
    public function edit(Request $request, Disposal $disposal): Response
    {
        $form = $this->createForm(DisposalType::class, $disposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'message.edited_succesfully');

            return $this->redirectToRoute('disposal_index', [
                'id' => $disposal->getId(),
            ]);
        }

        return $this->render('disposal/edit.html.twig', [
            'disposal' => $disposal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete specific disposal
     * @Route("/admin/disposal/{id}", name="disposal_delete", methods={"DELETE"})
     *
     * @param Request  $request
     * @param Disposal $disposal
     *
     * @return Response
     */
    public function delete(Request $request, Disposal $disposal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$disposal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($disposal);
            $entityManager->flush();
            $this->addFlash('success', 'message.deleted_succesfully');
        }

        return $this->redirectToRoute('disposal_index');
    }
}
