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

/**
 * @Route("/disposal")
 */
class DisposalController extends Controller
{
    /**
     * @Route("/", name="disposal_index", methods={"GET"})
     */
    public function index(DisposalRepository $disposalRepository): Response
    {
        return $this->render('disposal/index.html.twig', [
            'disposals' => $disposalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="disposal_new", methods={"GET","POST"})
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
     * @Route("/{id}/{user}", name="disposal_user_show", methods={"GET"})
     */
    public function userShow(Disposal $disposal, ShopRepository $shopRepository, $user): Response
    {
      $userId = $user;
      $user = $this->getUser();
      if ((int)$userId !== $user->getId()) {
        $this->addFlash('error','message.you_cant_view_this_disposal');
        $this->redirectToRoute('product_index');
      }

      return $this->render('disposal/user_show.html.twig', [
          'shop' => $shopRepository->findAll()[0],
          'disposal' => $disposal,
      ]);
    }

    /**
     * @Route("/{id}", name="disposal_show", methods={"GET"})
     */
    public function show(Disposal $disposal): Response
    {
        return $this->render('disposal/show.html.twig', [
            'disposal' => $disposal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="disposal_edit", methods={"GET","POST"})
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
