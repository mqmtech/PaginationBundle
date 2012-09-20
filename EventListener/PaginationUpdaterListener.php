<?php

namespace MQM\PaginationBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use MQM\PaginationBundle\Pagination\PaginationInterface;
use MQM\PaginationBundle\Pagination\PaginationSingleton;

class PaginationUpdaterListener
{
        private $pagination;

        public function __construct(PaginationInterface $pagination)
        {
            $this->pagination = $pagination;
        }

        public function onKernelRequest(GetResponseEvent $event)
        {
            PaginationSingleton::setInstance($this->pagination);
        }
}