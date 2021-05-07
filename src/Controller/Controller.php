<?php

namespace App\Controller;

use App\Repository\ApiRepository;
use App\Repository\AsyncApiRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{

    protected $apiRepository;
    protected $paginator;
    protected AsyncApiRepository $asyncApiRepository;

    public function __construct(
        ApiRepository $apiRepository,
        AsyncApiRepository $asyncApiRepository,
        PaginatorInterface $paginator)
    {
        $this->apiRepository = $apiRepository;
        $this->paginator = $paginator;
        $this->asyncApiRepository = $asyncApiRepository;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->asyncApiRepository->getCategories()->setResultVar('categories');

        $parameters += $this->asyncApiRepository->execute();


//        $parameters['category'] = $this->apiRepository->getCategories();
//        $parameters['categories'] = $categories;
        dump($parameters);
        return parent::render($view, $parameters, $response);
    }

}
