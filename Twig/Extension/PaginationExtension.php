<?php

namespace MQM\PaginationBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PaginationExtension extends \Twig_Extension
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getName()
    {
        return 'mqm_pagination.twig_extension';
    }

    public function getFunctions()
    {
        return array(
            'mqm_pagination_paginate' => new \Twig_Function_Method($this, 'getWebPagination', array(
                'is_safe' => array('html') // this enables raw-html output
            )),
        );
    }
    
    public function getFilters()
    {
        return array(
        );
    }
    
    public function getGlobals()
    {
        $pagination = $this->container->get('mqm_pagination.pagination_manager');
        
        return array('mqm_pagination' => array('pagination' => $pagination));
    }
    
    public function getWebPagination($view = null, array $parameters = array())
    {
        $view = $view == null ? 'MQMPaginationBundle:Pagination:pagination_bar.partialhtml.twig' : $view;
        $pagination = $this->container->get('mqm_pagination.pagination_manager');
        $parameters['pagination'] = $pagination;

        $content = $this->container->get('templating')->render($view, $parameters);
        return $content;
        
    }
}