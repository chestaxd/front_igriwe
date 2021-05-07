<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="{category}.%app.root_domain%", requirements={"category": "%app.categories%"})
 */
class MainController extends Controller
{
    /**
     * @Route("/", name="home_order_by_updateAt", defaults={"orderBy":"updatedAt"})
     * @Route("/top", name="home_order_by_rating", defaults={"orderBy":"rating"})
     */
    public function index($orderBy, Request $request): Response
    {
        $page = $request->get('p') ? $request->get('p') : 1;
        $ipp = $this->getParameter('app.ipp');
        $subdomain = $request->attributes->get('category');
        $games = $this->apiRepository->getGamesByCategory($subdomain, $page, $ipp, $orderBy);
        $meta = $this->apiRepository->getRequestMeta();
        $pagination = $this->paginator->paginate(range(1, $meta['totalItems']), $page, $ipp);
        return $this->render('category/category.html.twig', compact('games', 'pagination'));
    }

    /**
     * @Route("/{gameId}", name="game_page", requirements={"gameId"="\d+"})
     */
    public function game(int $gameId)
    {
        $this->asyncApiRepository->getGame($gameId)->setResultVar('game');
        $this->asyncApiRepository->getRelevantGames($gameId)->setResultVar('relevantGames');
//        $parameters = $this->asyncApiRepository->execute();
//
//        dump($parameters);
//        $game = $this->apiRepository->getGame($gameId);
//        $relevantGames = $this->apiRepository->getRelevantGames($gameId);
        return $this->render('game/game.html.twig');
    }

    /**
     * @Route("/", name="home", host="%app.root_domain%")
     */
    public function index_home(): Response
    {
//        $categories = $this->apiRepository->getCategories();
        return $this->render('home/index.html.twig');
    }
}