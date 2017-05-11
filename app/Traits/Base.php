<?php 

namespace Sage\Traits;

use Slim\Container;

trait Base 
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        } else {
            throw new Exception("The property {$property} does not exist in container...", 1);
        }
    }
}
