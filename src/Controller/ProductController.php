<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     *
     * @param Request            $request
     * @param ProductRepository  $productRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $productRepository->queryAllWithFilters(
                $request->query->getInt('category', 0),
                $request->query->getInt('shop', 0)
            ),
            $request->query->getInt('page', 1),
            Product::NUMBER_OF_ITEMS
        );

        return $this->render('product/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     *
     * @param Product $product
     *
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {
        $img = $product->getImg();
        $product->setImg(null);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formImg = $product->getImg();
            if (null !== $formImg) {
                $product->setImg($formImg);
            } else {
                $product->setImg($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
