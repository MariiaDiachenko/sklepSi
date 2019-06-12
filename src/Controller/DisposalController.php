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

/**
 * @Route("/admin/disposal")
 */
class DisposalController extends Controller
{
    /**
     * @Route("/", name="disposal_index", methods={"GET"})
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
     * @Route("/new", name="disposal_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $disposal = new Disposal();
        $form = $this->createForm(DisposalType::class, $disposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($disposal);
            $entityManager->flush();

            return $this->redirectToRoute('disposal_index');
        }

        return $this->render('disposal/new.html.twig', [
            'disposal' => $disposal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{user}", name="disposal_user_show", requirements={"id"="\d+", "user"="\d+"}, methods={"GET"})
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
        if ((int) $userId !== $user->getId()) {
            $this->addFlash('danger', 'message.you_cant_view_this_disposal');
            $this->redirectToRoute('product_index');
        }

        return $this->render('disposal/user_show.html.twig', [
            'shop' => $shopRepository->findAll()[0],
            'disposal' => $disposal,
        ]);
    }

    /**
     * @Route("/{id}", name="disposal_show", methods={"GET"})
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
     * @Route("/{id}/edit", name="disposal_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="disposal_delete", methods={"DELETE"})
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
        }

        return $this->redirectToRoute('disposal_index');
    }
}
