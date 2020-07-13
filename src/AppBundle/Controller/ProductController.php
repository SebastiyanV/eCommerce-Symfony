<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Form\ProductType;
use AppBundle\Service\Article\ProductServiceInterface;
use AppBundle\Service\Category\CategoryServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @var ProductServiceInterface
     */
    private $productService;

    /**
     * @var CategoryServiceInterface $categoryService
     */
    private $categoryService;

    /**
     * ProductController constructor.
     * @param ProductServiceInterface $productService
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(ProductServiceInterface $productService, CategoryServiceInterface $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/shop", name="view_all_products")
     */
    public function viewAllProducts()
    {
        $products = $this->productService->getAllPublicProducts();
        $categories = $this->categoryService->getAll();

        return $this->render("shop/shop_index.html.twig", ['products' => $products, 'categories' => $categories]);
    }

    /**
     * @Route("/shop/category/{id}", name="view_products_by_category")
     * @param int $id
     * @return Response
     */
    public function viewProductsByCategory(int $id)
    {
        $currentCategory = $this->categoryService->getOneById($id);
        $allCategories = $this->categoryService->getAll();

        if ($currentCategory->getParent()) {
            return $this->render(
                'shop/shop_index.html.twig',
                ['products' => $currentCategory->getProducts(), 'categories' => $allCategories]
            );
        }

        $parentProducts = $currentCategory->getProducts();
        $childrenProducts = [];

        foreach ($currentCategory->getChildren() as $child) {
            foreach ($child->getProducts() as $product) {
                $childrenProducts[] = $product;
            }
        }

        $allProducts = array_merge($parentProducts->toArray(), $childrenProducts);

        return $this->render('shop/shop_index.html.twig', ['products' => $allProducts, 'categories' => $allCategories]);

    }

    /**
     * @Route("/shop/product/{id}", name="view_product")
     * @param int $id
     * @return Response
     */
    public function viewProduct(int $id)
    {
        return $this->render(
            'shop/view_product.html.twig',
            [
                'product' => $this->productService->getOneById($id),
            ]
        );
    }

    /**
     * @Route("/admin/products/view/category/{id}", name="admin_products_view_by_category_id")
     * @param int $id
     * @return Response
     */
    public function adminViewProducts(int $id = null)
    {
        if (null === $id) {
            $products = $this->productService->getAll();

            return $this->render(
                'admin_panel/shop/products/view_products.html.twig',
                [
                    'products' => $products,
                ]
            );
        }

        $products = $this->productService->getAllByCategoryId($id);

        return $this->render(
            'admin_panel/shop/products/view_products.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/admin/products/create", name="admin_products_create", methods={"GET"})
     */
    public function adminCreateProduct()
    {
        return $this->render(
            'admin_panel/shop/products/create_product.html.twig',
            [
                'form' => $this->createForm(ProductType::class)->createView(),
            ]
        );


    }

    /**
     * @Route("/admin/products/create", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function adminCreateProductProcess(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );

                $product->setImage($fileName);

            } catch (FileException $ex) {

            }
            $product->setImage($fileName);

            $this->productService->save($product);


            return $this->redirectToRoute('admin_products_view_by_category_id');
        }

        return $this->redirectToRoute('admin_products_view_by_category_id');
    }

    /**
     * @Route("/admin/products/view/{id}", name="admin_products_view_by_id")
     * @param int $id
     * @return Response
     */
    public function adminViewProduct(int $id)
    {
        $product = $this->productService->getOneById($id);

        return $this->render('admin_panel/shop/products/view_one.html.twig', ['product' => $product]);
    }

}