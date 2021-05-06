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

    public function getGamesByCategory($categoryId, $page = 1, $ipp = 10)
    {
        return $this->apiCall('/categories/%categoryId%/games?page=%page%&itemsPerPage=%ipp%&isPublished=true',
            compact('categoryId', 'page', 'ipp'));
    }

    public function getGames($page = 1, $ipp = 10)
    {
        return $this->apiCall('/games?page=%page%&itemsPerPage=%ipp%', compact('page', 'ipp'));
    }


//    public function getCountryCities(int $countryId, int $page, $ipp = 60)
//    {
//        return $this->apiCall('/countries/%countryId%/cities?rating%5Bgt%5D=1&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
//            compact('countryId', 'page', 'ipp'));
//    }

}