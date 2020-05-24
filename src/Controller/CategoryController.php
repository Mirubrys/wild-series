<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route(
 *     "/category",
 *     name="category_"
 * )
 */
class CategoryController extends AbstractController
{
    /**
     * Display a dropdown witch contains all categories links
     *
     * @return Response
     */
    public function selectCategory() :Response
    {
        /* We get all categories */
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('embed/category_list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display a form to create a new category
     *
     * @param Request $request
     * @Route(
     *     "/add",
     *     name="add"
     * )
     * @return Response
     */
    public function add(Request $request) :Response
    {
        // Variable witch contains a success message
        $message = null;

        /* We instantiate a search form */
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        /* If the form is submitted, we create a new category in database */
        if ($form->isSubmitted()) {

            // We get the data from the form
            $category = $form->getData();

            // We instantiate the entity manager
            $entityManager = $this->getDoctrine()
                ->getManager();

            // We merge the new object with the database
            $entityManager->persist($category);
            $entityManager->flush();

            $message = 'La catégorie "'.$category->getName().'" a été correctement crée.';
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }

}