<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use AppBundle\Service\Category\CategoryServiceInterface;
use AppBundle\Service\User\UserServiceInterface;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @var UserServiceInterface $userService
     */
    private $userService;

    /**
     * @var CategoryServiceInterface $categoryService
     */
    private $categoryService;

    /**
     * CategoryController constructor.
     * @param UserServiceInterface $userService
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(UserServiceInterface $userService, CategoryServiceInterface $categoryService)
    {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/categories/view/{id}", name="admin_categories_view")
     * @param null|int $id
     * @return Response
     */
    public function viewAllCategories(int $id = null)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->findAll();
        if (null === $id) {
            return $this->render('admin_panel/shop/categories/view_categories.html.twig', ['categories' => $categories]);
        }

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(['id' => $id]);

        return $this->render('admin_panel/shop/categories/view_one_category.html.twig', ['category' => $category]);
    }

    /**
     * @Route("/admin/categories/create", name="admin_categories_create", methods={"GET"})
     */
    public function addCategory()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->findBy([], ['id' => 'DESC'], 7);

        return $this->render(
            'admin_panel/shop/categories/create_category.html.twig',
            [
                'form' => $this->createForm(CategoryType::class)->createView(),
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route("/admin/categories/create", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function addCategoryProcess(Request $request)
    {
        $allCategories = $this->categoryService->getAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if (null != $category->getParent()) {
            if (null != $category->getParent()->getParent()) {
                $this->addFlash(
                    'danger',
                    'This category already has parent! You can only create subcategories which dont have parent category!'
                );

                return $this->redirectToRoute('admin_categories_create');
            }
        }

        try {
            $this->categoryService->save($category);
        } catch (NotNullConstraintViolationException $exception) {
            $this->addFlash(
                'danger',
                'Please fill all fields correctly!'
            );

            return $this->redirectToRoute('admin_categories_create');
        } catch (UniqueConstraintViolationException $exception) {
            $this->addFlash(
                'danger',
                'This category already exists!'
            );

            return $this->redirectToRoute('admin_categories_create');
        }

        $this->addFlash('success', 'Successfully created category!');

        return $this->redirectToRoute('admin_categories_create');
    }

    /**
     * @Route("/admin/categories/edit/{id}", name="admin_categories_edit", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function editCategory($id)
    {

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->find($id);
        $categories = $this->categoryService->getAll();

        return $this->render(
            'admin_panel/shop/categories/edit_category.html.twig',
            [
                'form' => $this->createForm(CategoryType::class)->createView(),
                'category' => $category,
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route("/admin/categories/edit/{id}", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function editCategoryProcess(Request $request, $id)
    {

        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository(Category::class)
            ->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $this->categoryService->edit($category);

        $this->addFlash('success', 'Category was edited successfully!');

        return $this->redirectToRoute('admin_categories_view', ['categoryName' => $category->getName()]);
    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin_categories_delete")
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteCategory(int $id)
    {
        $category = $this->categoryService->getOneById($id);

        if (count($category->getChildren()) > 0) {
            $this->addFlash('danger', 'You cannot delete category which has a children categories!');

            return $this->redirectToRoute('admin_categories_view');
        }

        if (count($category->getProducts()) > 0) {
            $this->addFlash('danger', 'You cannot delete category which has an articles in it!');

            return $this->redirectToRoute('admin_categories_view');
        }

        $this->categoryService->remove($id);
        $this->addFlash('success', 'You deleted successfully a category!');

        return $this->redirectToRoute('admin_categories_view');
    }

}
