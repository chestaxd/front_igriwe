<?php


namespace App\Repository;


trait ApiRepositoryTrait
{
    /** @var string */
    private $apiPrefix = 'http://api.igriwe.pp.ua';


    public function getCategories()
    {
        return $this->apiCall('/categories');

    }

    public function getCategory($id)
    {
        return $this->apiCall('/category/%id%', compact('id'));
    }

    public function getGamesByCategory($categorySubdomain, $page = 1, $ipp = 10, $orderBy = 'createdAt')
    {
        return $this->apiCall('/categories/%categorySubdomain%/games?page=%page%&itemsPerPage=%ipp%&isPublished=true&order[%orderBy%]',
            compact('categorySubdomain', 'page', 'ipp', 'orderBy'));
    }

    public function getGames($page = 1, $ipp = 10, $orderBy = 'createdAt')
    {
        return $this->apiCall('/games?page=%page%&itemsPerPage=%ipp%&order[%orderBy%]',
            compact('page', 'ipp', 'orderBy'));
    }

    public function getGame($gameId)
    {
        return $this->apiCall('/game/%gameId%', compact('gameId'));
    }

    public function getRelevantGames($gameId)
    {
        return $this->apiCall('/game/%gameId%/relevant', compact('gameId'));
    }

//    public function getCountryCities(int $countryId, int $page, $ipp = 60)
//    {
//        return $this->apiCall('/countries/%countryId%/cities?rating%5Bgt%5D=1&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
//            compact('countryId', 'page', 'ipp'));
//    }
}