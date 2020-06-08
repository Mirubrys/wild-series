<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route("/wild", name="Wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     * @return Response
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * Display the show page for series
     * @author Michel-Ange MENDES DOS SANTOS
     * @Route("/show/{slug}",
     *     requirements={"slug"="[a-z0-9-]+"},
     *     name="show")
     * @param string $slug = "Aucune série sélectionnée, veuillez choisir une série"
     * @return Response
     */
    public function show(string $slug="Aucune série sélectionnée, veuillez choisir une série") :Response
    {
        // We replace all dash by a white space
        $slug = str_replace("-", " ", $slug);
        // We place all first letter for each word in uppercase
        $slug = ucwords($slug);

        // We send the view with the slug as variable
        return $this->render('wild/show.html.twig', [
           'slug' => $slug,
        ]);
    }
}