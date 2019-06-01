<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Form\ShopType;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/shop")
 */
class ShopController extends Controller
{
    /**
     * @Route("/", name="shop_index", methods={"GET"})
     */
    public function index(Request $request, ShopRepository $shopRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
           $shopRepository->queryAll(),
           $request->query->getInt('page', 1),
           Shop::NUMBER_OF_ITEMS
       );

        return $this->render('shop/index.html.twig', [
            'shops' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="shop_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $shop = new Shop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shop);
            $entityManager->flush();

            return $this->redirectToRoute('shop_index');
        }

        return $this->render('shop/new.html.twig', [
            'shop' => $shop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_show", methods={"GET"})
     */
    public function show(Shop $shop): Response
    {
        return $this->render('shop/show.html.twig', [
            'shop' => $shop,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="shop_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Shop $shop): Response
    {
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop_index', [
                'id' => $shop->getId(),
            ]);
        }

        return $this->render('shop/edit.html.twig', [
            'shop' => $shop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Shop $shop): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shop->getId(), $request->request->get('_token'))) {
          if ($shop->hasProducts()) {
            $this->addFlash('danger', 'message.cant_delete_shop_containing_products');
            return $this->redirect($this->generateUrl('shop_show', ['id'=>$shop->getId()]));
          }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shop_index');
    }
}
