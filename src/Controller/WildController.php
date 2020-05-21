<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route(
 *     "/wild",
 *     name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route(
     *     "/index",
     *     name="index")
     * @return Response
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route(
     *     "/show/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="show")
     * @return Response
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException(
                    'No slug has been sent to find a program in program\'s table.'
                );
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this
                ->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     * @param string $categoryName
     * @Route(
     *     "/category/{categoryName<^[a-z-]+$>}",
     *     defaults={"categoryName" = null},
     *     name="show_category")
     * @return Response
     */
    public function showByCategory(?string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException(
                    'No category has been sent.'
                );
        }
        $categoryName = ucwords(trim(strip_tags($categoryName)),"-");

        $category = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                "name" => $categoryName
            ]);

        if (!$category) {
            throw $this
                ->createNotFoundException(
                    'No program in the '.$categoryName.' category.'
                );
        }

        $programs = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                // Search programs by category
                ["category" => $category->getId()],
                // Ordered by id
                ["id" => 'desc'],
                // Limited by 3 results
                3,
                // Offset
                0
            );

        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }
}
