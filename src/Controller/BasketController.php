<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BasketController extends Controller
{
    const SESSION_PREFIX = 'basket_controller_session_bag_';

    public function widget(SessionInterface $session, Request $request, ProductRepository $productRepository)
    {
        $basket = $this->getBasket($session);
        $basketProducts = $productRepository->findBy([ 'id' => array_values($basket)]);

        return $this->render('basket/widget.html.twig', [
          'basket_products' => $basketProducts,
        ]);
    }

    /**
     * @Route("/basket/add/{id}", name="basket_add", methods={"GET", "POST"})
     */
    public function add($id, Request $request, ProductRepository $productRepository, SessionInterface $session): RedirectResponse
    {
       $id = intval($id);
       if (is_int($id) && $productRepository->find($id)) {
         $session->set($this->makeKey($id), $id);
         $this->addFlash('success', 'message.product_added_to_basket');
       }  else {
         $this->addFlash('error', 'message.unable_to_add_this_product_to_basket');
       }

       return $this->redirect($this->getRefererUrl($request));
    }

    /**
     * @Route("/basket/remove/{id}", name="basket_remove", methods={"GET", "POST"})
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
            $session->remove($key);
        }

        return $this->redirect($this->getRefererUrl($request));
    }

    private function getRefererUrl(Request $request): string
    {
        return $request->headers->get('referer');
    }

    private function getBasket(SessionInterface $session): array
    {
        return array_filter(
            $session->all(),
            function ($key) {
                return strpos($key, self::SESSION_PREFIX) === 0;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Prefix id with $this->wishlistPrefix
     */
    private function makeKey(int $id): string
    {
        return self::SESSION_PREFIX . $id;
    }
}
