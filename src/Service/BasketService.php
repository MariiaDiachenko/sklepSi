<?php
/**
 * Basket service.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BasketService.
 */
class BasketService
{
  /**
  * string session variable previx
  */
  const SESSION_PREFIX = 'basket_controller_session_bag_';


  /**
   * Makes b
   * @param SessionInterface  $session
   * @param ProductRepository $productRepository
   *
   * @return array [Product $product, int $quantity]
   */
   public function makeBasketForRender(SessionInterface $session, ProductRepository $productRepository): array
   {
       $basket = $this->getBasket($session);
       $basketProducts = $productRepository->findBy(['id' => array_keys($basket)]);

       return array_map(null, $basketProducts, $basket);
   }

   /**
   * Get address from wchich request was send
   * @param Request $request
   *
   * @return string
   */
   public function getRefererUrl(Request $request): string
   {
       return $request->headers->get('referer');
   }

   /**
   * Get array [key_containing_product_id => quantity]
   * @param SessionInterface $session
   *
   * @return string
   */
   public function getBasket(SessionInterface $session): array
   {
       $basket = array_filter(
           $session->all(),
           function ($key) {
               return 0 === strpos($key, self::SESSION_PREFIX);
           },
           ARRAY_FILTER_USE_KEY
       );

       return $this->removePrefix($basket);
   }

   /**
   * Get array of [string_product_id => quantity]
   * @param array $basket
   *
   * @return array
   */
   public function removePrefix(array $basket): array
   {
       $productIdQty = [];
       foreach ($basket as $key => $value) {
           $productIdQty[ltrim($key, self::SESSION_PREFIX)] = $value;
       }

       return $productIdQty;
   }

  /**
  * Prefix id with $this->wishlistPrefix.
  * @param int $id
  *
  * @return string
  */
   public function makeKey(int $id): string
   {
       return self::SESSION_PREFIX.$id;
   }
}
