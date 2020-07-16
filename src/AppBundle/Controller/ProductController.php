<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Entity\ProductImage;
use AppBundle\Form\ProductImageType;
use AppBundle\Form\ProductType;
use AppBundle\Service\Product\ProductServiceInterface;
use AppBundle\Service\Category\CategoryServiceInterface;
use AppBundle\Service\ProductImage\ProductImageServiceInterface;
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
     * @var ProductImageServiceInterface $imageService
     */
    private $imageService;

    /**
     * ProductController constructor.
     * @param ProductServiceInterface $productService
     * @param CategoryServiceInterface $categoryService
     * @param ProductImageServiceInterface $imageService
     */
    public function __construct(
        ProductServiceInterface $productService,
        CategoryServiceInterface $categoryService,
        ProductImageServiceInterface $imageService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->imageService = $imageService;
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

        $this->productService->save($product);


        return $this->redirectToRoute('admin_products_view_by_category_id');
    }

    /**
     * @Route("/admin/products/view/{id}", name="admin_products_view_by_id")
     * @param int $id
     * @return Response
     */
    public
    function adminViewProduct(
        int $id
    ) {
        $product = $this->productService->getOneById($id);

        return $this->render('admin_panel/shop/products/view_one.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/admin/products/edit/{id}", name="admin_products_edit", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public
    function adminEditProduct(
        int $id
    ) {
        $product = $this->productService->getOneById($id);

        return $this->render(
            'admin_panel/shop/products/edit_product.html.twig',
            [
                'form' => $this->createForm(ProductType::class)->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @Route("/admin/products/edit/{id}", methods={"POST"})
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public
    function adminEditProductProcess(
        int $id,
        Request $request
    ) {
        $product = $this->productService->getOneById($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $this->productService->edit($product);
        $this->addFlash('success', 'Product updated successfully!');

        return $this->redirectToRoute('admin_products_edit', ['id' => $product->getId()]);
    }

    /**
     * @Route("/admin/products/{id}/images/add", name="admin_products_images_add", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public
    function addImage(
        int $id
    ) {
        return $this->render(
            'admin_panel/shop/products/images/add_image.html.twig',
            [
                'product' => $this->productService->getOneById($id),
                'form' => $this->createForm(ProductImageType::class)->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/products/{id}/images/add", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public
    function addImageProcess(
        Request $request,
        int $id
    ) {
        $image = new ProductImage();
        $form = $this->createForm(ProductImageType::class, $image);
        $form->handleRequest($request);
        $image->setProduct($this->productService->getOneById($id));

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get('imageFileName')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );

                $image->setName($fileName);

            } catch (FileException $ex) {

            }

            $image->setName($fileName);

            $this->imageService->add($image);

            return $this->redirectToRoute('admin_products_images_add', ['id' => $image->getProduct()->getId()]);
        }

        return $this->redirectToRoute('admin_products_images_add', ['id' => $image->getProduct()->getId()]);
    }

    /**
     * @Route("/admin/products/{productId}/images/{imageId}/set-main", name="admin_products_images_set_main")
     * @param int $productId
     * @param int $imageId
     * @return RedirectResponse
     */
    public function setMainImage(int $productId, int $imageId)
    {
        $product = $this->productService->getOneById($productId);

        /** @var ProductImage $image */
        foreach ($product->getImages() as $image) {
            if ($image->isTopImage()) {
                $image->setTopImage(false);
                $this->imageService->edit($image);
            }
        }
        $currentImage = $this->imageService->getOneById($imageId);
        $currentImage->setTopImage(true);
        $this->imageService->edit($image);

        return $this->redirectToRoute('admin_products_images_add', ['id' => $productId]);
    }

    /**
     * @Route("/admin/products/{productId}/images/{imageId}/delete", name="admin_products_images_delete")
     * @param int $productId
     * @param int $imageId
     * @return RedirectResponse
     */
    public function deleteImage(int $productId, int $imageId)
    {
        $this->imageService->delete($this->imageService->getOneById($imageId));

        return $this->redirectToRoute('admin_products_images_add', ['id' => $productId]);
    }
}