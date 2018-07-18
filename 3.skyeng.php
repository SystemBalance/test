<?php

namespace src\Integration;

class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }
    
    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
        // returns a response from external service
    }
}


namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager
{
    /**
     * @var $cache CacheItemPoolInterface
     */
    protected $cacheItemPool;
    /**
     * @var $logger LoggerInterface
     */
    protected $logger;
    /**
     * @var $provider DataProvider
     */
    protected $dataProvider;

    /**
     * @param $dataProvider DataProvider
     */
    public function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }
    
    /**
     * @param $logger LoggerInterface
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @param $cacheItemPool CacheItemPoolInterface
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }
    

    
    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cacheItemPool->getItem($cacheKey);
            
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return [];
    }
    
    protected function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}


$cacheItemPool = new CacheItemPool();
$logger = new Logger();
$dataProvider = new DataProvider($host, $user, $password);


$DecoratorManager = new DecoratorManager($dataProvider);
$DecoratorManager->setCacheItemPool($cacheItemPool);
$DecoratorManager->setLogger($logger);

$DecoratorManager->getResponse();

