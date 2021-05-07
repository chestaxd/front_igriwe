<?php


namespace App\Repository;


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

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @param array $var
     * @param null $requestNum
     *
     * @return $this
     */
    public function setResultVar($var, $requestNum = null)
    {
        if (empty($requestNum)) {
            $requestNum = sizeof($this->requests);
        }
        $requestNum--;
        $this->resultVars[$requestNum] = $var;
        return $this;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute()
    {
        $result = [];
        foreach ($this->client->stream($this->requests) as $response => $chunk) {
            if (!$chunk->isFirst() && !$chunk->isLast()) {
                foreach ($this->requests as $num => $request) {
                    if ($request == $response && isset($this->resultVars[$num])) {
                        $res = $response->toArray();
                        $result[$this->resultVars[$num]] = isset($res['hydra:member']) ? $res['hydra:member'] : $res;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return $this
     */
    public function apiCall(string $url, array $params = [])
    {
        $url = $this->apiPrefix . $url;;
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