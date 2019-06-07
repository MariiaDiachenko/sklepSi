<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FrontPageController extends Controller
{
    /**
     * @Route("/", name="front_page")
     */
    public function index()
    {
        return $this->redirectToRoute('product_index');
    }
}
