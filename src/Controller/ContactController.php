<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShopRepository;
use App\Entity\Shop;
use Symfony\Component\HttpFoundation\Response;

/**
*  Contact controller class
*/
class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact", methods={"GET"})
     *
     * @param ShopRepository $shopRepository
     *
     * @return Response
     */
    public function index(ShopRepository $shopRepository): Response
    {
        $shopArray = $shopRepository->findBy([],[],1);

        return $this->render('contact/index.html.twig', [
            'shop' => isset($shopArray[0]) ? $shopArray[0] : new Shop()
        ]);
    }
}
