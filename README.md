README
======

What is MQMPaginationBundle ?
----------------------------

**MQMPagination Bundle** is a Symfony2 bundle that lets you build pagination into your website.

This bundle is part of [MQMShoppingBox][1], a bunch of bundles to help you build e-commerce sites out of the box.

Check [ShoppingBox] for see this bundle on action.

Requirements
------------

This bundle takes advantages of some libraries and bundles. You will need:

 * [DoctrineExtensions][2] for sluggable and tree extensions (configuration needed described below in **Installation**).

Installation
------------

Add MQMPaginationBundle to your vendors:
	
    You have two options, by modifying your dep file or creating a submodule
	
        - Option 1: Editing deps file:
            //deps	
            // ...
            [PaginationBundle]
            git=http://github.com/mqmtech/PaginationBundle.git
            target=/bundles/MQM/Bundle/PaginationBundle
	
            $ php bin/vendors update

        - Option 2: submodule command
            $ git submodule add git://github.com/mqmtech/PaginationBundle.git vendor/bundles/MQM/Bundle/PaginationBundle

Add PaginationBundle to your autoload:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'MQM' => __DIR__.'/../vendor/bundles',
        // ...
    ));

Add PaginationBundle to your application kernel:

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new MQM\Bundles\PaginationBundle\MQMPaginationBundle(),
        );
        // ...

        return $bundles;
    }

Add MQMPaginationBundle routing rules to your application (this should be the last entry of *routing.yml*):

    # app/config/routing.yml
    # ...
    MQMPaginationBundle:
        resource: "@MQMPaginationBundle/Resources/config/routing.yml"
        prefix:   /

Add assets to your web directory:

    $ ./app/console assets:install --symlink web

Rebuild the model and update your schema:

    $ ./app/console doctrine:generate:entities
    $ ./app/console doctrine:schema:update --force

Configuration
-------------

    # app/config/config.yml
    mqm_pagination:
        limit_per_page: 6

Security
--------

Templates
---------

Extra
-----

How to use
----------

Other
-----
