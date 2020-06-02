<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route(
 *     "/wild",
 *     name="wild_"
 * )
 */
class WildController extends AbstractController
{
    /**
     * Display a dropdown witch contains all administrations links
     *
     * @return Response
     */
    public function adminTools() :Response
    {
        return $this->render('embed/_admin_tools_list.html.twig');
    }

    /**
     * Display all available programs
     *
     * @Route(
     *     "/index",
     *     name="index"
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) :Response
    {
        /* We instantiate a search form */
        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);

        /* If the user make a search, we get the matching ones,
        Else, we get all programs */
        if ($form->isSubmitted()) {
            // We get the _POST
            $search = $form->getData()['searchField'];

            /* We query the table 'program' in db */
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->createQueryBuilder('p')
                ->where('p.title LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->getQuery()
                ->getResult();
        } else {
            /* We get all programs */
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findAll();

            /* If no program where found, we throw a http code 404 */
            if (!$programs) {
                throw $this->createNotFoundException(
                    'No program found in program\'s table.'
                );
            }
        }

        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs,
            'form' => $form->createView(),
            'search' => $form->isSubmitted()
        ]);
    }

    /**
     * Display the selected program
     *
     * @param string $slug The slugger
     * @Route(
     *     "/show/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="show"
     * )
     * @return Response
     */
    public function show(?string $slug):Response
    {
        /* If no slug is given, we throw a http code 404 */
        if (!$slug) {
            throw $this
                ->createNotFoundException(
                    'No slug has been sent to find a program in program\'s table.'
                );
        }

        // We format the slug
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        /* We get the program by its title */
        $program = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        /* If there is no program returned, we throw a http code 404 */
        if (!$program) {
            throw $this
                ->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        // We get all seasons of the got program
        $seasons = $program->getSeasons();

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Display the programs of the selected category
     *
     * @param string $categoryName
     * @Route(
     *     "/show/category/{categoryName<^[a-z-]+$>}",
     *     defaults={"categoryName" = null},
     *     name="show_category"
     * )
     * @return Response
     */
    public function showByCategory(?string $categoryName) :Response
    {
        /* If there is not category given, we throw a http code 404 */
        if (!$categoryName) {
            throw $this
                ->createNotFoundException(
                    'No category has been sent.'
                );
        }

        // We format the category name
        $categoryName = ucwords(trim(strip_tags($categoryName)),"-");

        /* We get the category by its name */
        $category = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                "name" => $categoryName
            ]);

        /* If there is no category returned, we throw a http code 404 */
        if (!$category) {
            throw $this
                ->createNotFoundException(
                    'No program in the '.$categoryName.' category.'
                );
        }

        /* We get the 3 last programs of the category */
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

    /**
     * Display the selected season's page
     *
     * @param int $id
     * @return Response
     * @Route(
     *     "/show/season/{id<^\d+$>}",
     *     defaults={"id" = null},
     *     name = "show_season"
     * )
     */
    public function showBySeason(int $id) :Response
    {
        /* If there is no id given, we throw a http code 404 */
        if(!$id) {
            throw $this
                ->createNotFoundException(
                    'No season has been send.'
                );
        }

        /* We get the season by it's id */
        $season = $this
            ->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);

        /* If there is no season returned, we throw a http code 404 */
        if (!$season) {
            throw $this
                ->createNotFoundException(
                    'Doesn\'t exists'
                );
        }

        // We get the program relative to the season
        $program = $season->getProgram();

        // We get the episodes of the season
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * Display the selected episode's page
     *
     * @param Episode $episode
     * @Route(
     *     "/show/episode/{id<^\d+$>}",
     *     defaults={"id" = null},
     *     name = "show_episode"
     * )
     * @return Response
     */
    public function showEpisode(Episode $episode) :Response
    {
        // We get the season relative to the episode
        $season = $episode->getSeason();

        // We get the program relative to the episode
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }
}
