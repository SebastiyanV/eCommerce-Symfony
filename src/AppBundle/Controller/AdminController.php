<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Form\ProductType;
use AppBundle\Service\Product\ProductServiceInterface;
use AppBundle\Service\Category\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @var ProductServiceInterface
     */
    private $articleService;

    /**
     * @var CategoryServiceInterface $categoryService
     */
    private $categoryService;

    /**
     * ProductController constructor.
     * @param ProductServiceInterface $articleService
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(ProductServiceInterface $articleService, CategoryServiceInterface $categoryService)
    {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function adminDashboard() {
        return $this->render('admin_panel/home/index.html.twig');
    }

//    /**
//     * @Route("/admin/article/view/category/{id}", name="admin_article_view")
//     * @param null|int $id
//     * @return Response
//     */
//    public function viewArticlesByCategoryId(int $id = null)
//    {
//
//        $category = $this->getDoctrine()->getRepository(Category::class)
//            ->findOneBy(['id' => $id]);
//
//        if (null === $id) {
//            $category = $this->categoryService->getAll();
//        }
//
//        $categories = $this->categoryService->getAll();
//
//        $articles = $this->getDoctrine()->getRepository(Product::class)
//            ->findBy(['category' => $category]);
//
//        return $this->render(
//            'admin/articles/view_all.html.twig',
//            ['articles' => $articles, 'categories' => $categories]
//        );
//    }
//
//    /**
//     * @Route("/admin/article/view/id/{id}", name="admin_article_view_by_id")
//     * @param int $id
//     * @return Response
//     */
//    public function viewArticleById(int $id)
//    {
//        $article = $this->articleService->getOneById($id);
//
//        return $this->render('admin/articles/view_one.html.twig', ['article' => $article]);
//    }
//
//    /**
//     * @Route("/admin/article/create", name="admin_article_create", methods={"GET"})
//     */
//    public function addArticleGet()
//    {
//        return $this->render(
//            'admin/articles/create.html.twig',
//            [
//                'form' => $this->createForm(ProductType::class)->createView(),
//                'categories' => $this->categoryService->getAll(),
//            ]
//        );
//    }
//
//    /**
//     * @Route("/admin/article/create", methods={"POST"})
//     * @param Request $request
//     * @return Response
//     */
//    public function addArticlePost(Request $request)
//    {
//
//        $article = new Product();
//        $form = $this->createForm(ProductType::class, $article);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            /** @var UploadedFile $file */
//            $file = $form->get('image')->getData();
//            $fileName = md5(uniqid()).'.'.$file->guessExtension();
//
//            try {
//                $file->move(
//                    $this->getParameter('upload_directory'),
//                    $fileName
//                );
//
//                $article->setImage($fileName);
//
//            } catch (FileException $ex) {
//
//            }
//            $article->setImage($fileName);
//
//            $this->articleService->save($article);
//
//
//            return $this->redirectToRoute('admin_article_view_by_id', ['id' => $article->getId()]);
//        }
//
//    }
}
