<?php


namespace App\Repository;


use App\Service\ApiResponseDecorator;
use App\Service\PagePrototypeTranslator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AsyncApiRepository
 *
 * @package App\Repository
 */
class AsyncApiRepository
{
    use ApiRepositoryTrait;

    /** @var HttpClientInterface */
    private $client;

    /** @var array */
    private $requests;

    /** @var array */
    private $resultVars;

    private $pageTranslationService;

    public function __construct(HttpClientInterface $client, PagePrototypeTranslator $pageTranslationService)
    {
        $this->client = $client;
        $this->pageTranslationService = $pageTranslationService;
    }


    /**
     * @param array $var
     * @param null  $requestNum
     *
     * @return $this
     */
    public function setResultVar(&$var, $requestNum = null)
    {
        if (!isset($var)) {
            $var = [];
        }
        if (empty($requestNum)) {
            $requestNum = sizeof($this->requests);
        }
        $requestNum--;
        $this->resultVars[$requestNum] = &$var;
        return $this;
    }

    /**
     * @return bool
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute()
    {
        foreach ($this->client->stream($this->requests) as $response => $chunk) {
            if (!$chunk->isFirst() && !$chunk->isLast()) {
                foreach ($this->requests as $num => $request) {
                    if ($request == $response && isset($this->resultVars[$num])) {
                        $res = $response->toArray();
                        $this->resultVars[$num] = ApiResponseDecorator::decorate((isset($res['hydra:member'])) ? $res['hydra:member'] : $res,
                            $this->pageTranslationService);
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return $this
     */
    public function apiCall(string $url, array $params = [])
    {
        $url = $this->apiPrefix . $url;;
        $params['locale'] = $this->locale;
        foreach ($params as $param => $value) {
            $url = str_replace("%{$param}%", $value, $url);
        }
        try {
            $this->requests[] = $this->client->request('GET', $url);
        } catch (TransportExceptionInterface $e) {
        }
        return $this;
    }
}