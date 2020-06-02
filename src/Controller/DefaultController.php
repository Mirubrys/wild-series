<?php


namespace App\Controller;


use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        /* We get the 3 last programs */
        $programs = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
            // Search programs by category
                [],
                // Ordered by id
                ["id" => 'desc'],
                // Limited by 3 results
                3,
                // Offset
                0
            );

        return $this->render('home.html.twig', [
            'programs' => $programs,
        ]);
    }
}