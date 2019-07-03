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

  /** SessionInterface $session */
    private $session;

  /**
   * Constructor
   * @param SessionInterface $session [description]
   */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

  /**
    * Make basket controller for render
    * @param  ProductRepository $productRepository
    *
    * @return array
    */
    public function makeBasketForRender(ProductRepository $productRepository): array
    {
        $basket = $this->getBasket($this->session);
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
   *
   * @return array
   */
    public function getBasket(): array
    {
        $basket = array_filter(
            $this->session->all(),
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
  * @param int $productId
  *
  * @return string
  */
    public function makeKey(int $productId): string
    {
        return self::SESSION_PREFIX.$productId;
    }

    /**
     * Clears basket
     */
    public function clear(): void
    {
        $keys = array_keys($this->getBasket($this->session));
        foreach ($keys as $key) {
             $this->session->remove(self::SESSION_PREFIX.$key);
        }
    }
}
