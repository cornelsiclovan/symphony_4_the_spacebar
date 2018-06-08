<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 07/06/2018
 * Time: 11:05
 */

namespace App\Helper;


use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
    }

    public function logInfo(string $message, array $context = []){
        if($this->logger){
            $this->logger->info($message, $context);
        }
    }
}