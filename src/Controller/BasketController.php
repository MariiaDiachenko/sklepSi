<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ShopRepository;
use App\Repository\ProductRepository;
use App\Repository\DisposalRepository;
use App\Entity\Product;
use App\Entity\DisposalDetails;
use App\Entity\Disposal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AddressType;

class BasketController extends Controller
{
    const SESSION_PREFIX = 'basket_controller_session_bag_';

    public function widget(SessionInterface $session, Request $request, ProductRepository $productRepository)
    {
        $basketProducts = $this->makeBasketForRender($session, $productRepository);

        return $this->render('basket/widget.html.twig', [
          'basket_products' => $basketProducts,
        ]);
    }

    /**
     * @Route("/basket/checkout", name="basket_checkout", methods={"GET", "POST"})
     */
    public function basketCheckout(
      Request $request,
      SessionInterface $session,
      ProductRepository $productRepository,
      DisposalRepository $disposalRepository,
      ShopRepository $shopRepository
      ): Response
    {
      $basketProducts = $this->makeBasketForRender($session, $productRepository);

      $this->denyAccessUnlessGranted('ROLE_USER');

      $form = $this->createForm(AddressType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $shop = $shopRepository->findAll()[0];
        $user = $this->getUser();

        $disposal = new Disposal();
        $disposal->setUser($user);
        $disposal->setStatus(Disposal::STATUS_WAITING_FOR_PAYMENT);
        $disposal->setAddress($form->getData()['address']);

        foreach ($this->makeBasketForRender($session, $productRepository) as $productQty) {
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

        return $this->redirectToRoute('disposal_user_show', ['id' => $disposal->getId(), 'user' => $user->getId()]);
      }

      return $this->render('basket/checkout.html.twig', [
        'form' => $form->createView(),
        'basket_products' => $basketProducts,
      ]);
    }

    /**
     * @Route("/basket/add/{id}", name="basket_add", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function add($id, Request $request, ProductRepository $productRepository, SessionInterface $session): RedirectResponse
    {
       $id = intval($id);
       if (is_int($id) && $productRepository->find($id)) {
         $productKey = $this->makeKey($id);
         $quantity = $session->get($productKey) ?? 0;
         $quantity++;
         $session->set($this->makeKey($id), $quantity);

         $this->addFlash('success', 'message.product_added_to_basket');
       }  else {
         $this->addFlash('error', 'message.unable_to_add_this_product_to_basket');
       }

       return $this->redirect($this->getRefererUrl($request));
    }

    /**
     * @Route("/basket/remove/{id}", name="basket_remove", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function remove($id, Request $request, SessionInterface $session): RedirectResponse
    {
        $id = intval($id);
        $session->remove(
            $this->makeKey($id)
        );

        return $this->redirect($this->getRefererUrl($request));
    }

    /**
     * @Route("/basket/clear", name="basket_clear", methods={"GET", "POST"})
     */
    public function clear(Request $request, SessionInterface $session): RedirectResponse
    {
        $keys = array_keys($this->getBasket($session));
        foreach ($keys as $key) {
            $session->remove(self::SESSION_PREFIX . $key);
        }

        return $this->redirect($this->getRefererUrl($request));
    }

    private function makeBasketForRender(SessionInterface $session, ProductRepository $productRepository): array
    {
      $basket = $this->getBasket($session);
      $basketProducts = $productRepository->findBy([ 'id' => array_keys($basket)]);
      return array_map(null, $basketProducts, $basket);
    }

    private function getRefererUrl(Request $request): string
    {
        return $request->headers->get('referer');
    }

    private function getBasket(SessionInterface $session): array
    {
        $basket = array_filter(
            $session->all(),
            function ($key) {
                return strpos($key, self::SESSION_PREFIX) === 0;
            },
            ARRAY_FILTER_USE_KEY
        );

        return $this->removePrefix($basket);
    }

    private function removePrefix(array $basket)
    {
      $productIdQty = [];
      foreach ($basket as $key => $value) {
        $productIdQty[ltrim($key, self::SESSION_PREFIX)] = $value;
      }

      return $productIdQty;
    }

    /**
     * Prefix id with $this->wishlistPrefix
     */
    private function makeKey(int $id): string
    {
        return self::SESSION_PREFIX . $id;
    }
}
