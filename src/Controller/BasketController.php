<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ShopRepository;
use App\Repository\ProductRepository;
use App\Repository\DisposalRepository;
use App\Entity\DisposalDetails;
use App\Entity\Disposal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AddressType;
use App\Service\BasketService;

/**
* BasketController class
*/
class BasketController extends Controller
{
    /**
     * Basket widget for render in template
     * @param  ProductRepository $productRepository
     * @param  BasketService     $basketService
     *
     * @return Response
     */
    public function widget(ProductRepository $productRepository, BasketService $basketService): Response
    {
        $basketProducts = $basketService->makeBasketForRender($productRepository);

        return $this->render('basket/widget.html.twig', [
            'basket_products' => $basketProducts,
        ]);
    }

    /**
     * @Route("/basket/checkout", name="basket_checkout", methods={"GET", "POST"})
     *
     * Basket Checkout
     *
     * @param  Request           $request           [description]
     * @param  ProductRepository $productRepository [description]
     * @param  BasketService     $basketService     [description]
     *
     * @return Response                             [description]
     */
    public function basketCheckout(Request $request, ProductRepository $productRepository, BasketService $basketService): Response
    {
        $basketProducts = $basketService->makeBasketForRender($productRepository);

        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(AddressType::class, new Disposal());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (count($basketProducts) < 1) {
                $this->addFlash('danger', 'message.cant_buy_empty_basket');

                return $this->redirectToRoute('basket_checkout');
            }

            $user = $this->getUser();

            $disposal = $form->getData();
            $disposal->setUser($user);
            $disposal->setStatus(Disposal::STATUS_WAITING_FOR_PAYMENT);


            foreach ($basketService->makeBasketForRender($productRepository) as $productQty) {
                $detail = new DisposalDetails();
                $product = $productQty[0];
                $qty = $productQty[1];

                $detail->setQuantity($qty);
                $detail->setCopiedPrice($product->getPrice());
                $detail->setProductName($product->getName());

                $disposal->addDisposalDetail($detail);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($disposal);
            $entityManager->flush();

            $this->addFlash('success', 'message.roder_successfully_saved');

            $basketService->clear();

            return $this->redirectToRoute('disposal_user_show', ['id' => $disposal->getId(), 'user' => $user->getId()]);
        }

        $totalPrice = 0;
        foreach ($basketProducts as $productQty) {
            $localPrice = $productQty[0]->getPrice() * $productQty[1];
            $totalPrice += $localPrice;
        }

        return $this->render('basket/checkout.html.twig', [
            'form' => $form->createView(),
            'basket_products' => $basketProducts,
            'total_price' => $totalPrice,
        ]);
    }

    /**
     * @Route("/basket/add/{productId}", name="basket_add", requirements={"id"="\d+"}, methods={"GET", "POST"})
     *
     * Adds new product to basket
     * @param  int               $productId
     * @param  Request           $request
     * @param  ProductRepository $productRepository
     * @param  SessionInterface  $session
     * @param  BasketService     $basketService
     *
     * @return RedirectResponse
     */
    public function add($productId, Request $request, ProductRepository $productRepository, SessionInterface $session, BasketService $basketService): RedirectResponse
    {
        $productId = intval($productId);
        if (is_int($productId) && $productRepository->find($productId)) {
            $productKey = $basketService->makeKey($productId);
            $quantity = $session->get($productKey) ?? 0;
            ++$quantity;
            $session->set($basketService->makeKey($productId), $quantity);

            $this->addFlash('success', 'message.product_added_to_basket');

            return $this->redirect($basketService->getRefererUrl($request));
        }

        $this->addFlash('danger', 'message.unable_to_add_this_product_to_basket');

        return $this->redirect($basketService->getRefererUrl($request));
    }

    /**
     * @Route("/basket/remove/{productId}", name="basket_remove", requirements={"productId"="\d+"}, methods={"GET", "POST"})
     *
     * @param  int              $productId
     * @param  Request          $request
     * @param  SessionInterface $session
     * @param  BasketService    $basketService
     *
     * @return RedirectResponse
     */
    public function remove($productId, Request $request, SessionInterface $session, BasketService $basketService): RedirectResponse
    {
        $productId = intval($productId);
        $session->remove(
            $basketService->makeKey($productId)
        );

        return $this->redirect($basketService->getRefererUrl($request));
    }

    /**
     * @Route("/basket/clear", name="basket_clear", methods={"GET", "POST"})
     *
     * @param  Request       $request       [description]
     * @param  BasketService $basketService [description]
     *
     * @return RedirectResponse                [description]
     */
    public function clear(Request $request, BasketService $basketService): RedirectResponse
    {
        $basketService->clear();

        return $this->redirect($basketService->getRefererUrl($request));
    }
}
