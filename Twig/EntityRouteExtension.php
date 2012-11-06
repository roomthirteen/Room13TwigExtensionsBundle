<?php

namespace Room13\TwigExtensionsBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
use Room13\TwigExtensionsBundle\Exception\PropertyNotFoundException;
use Room13\TwigExtensionsBundle\Exception\InvalidArgumentException;

class EntityRouteExtension extends \Twig_Extension
{

    /**
     * @var RouterInterface
     */
    private $router;

    private $prefix;

    public function __construct(RouterInterface $router,$prefix)
    {
        $this->router = $router;
        $this->prefix = $prefix;
    }

    /**
     * Generates a route url from an entity and a route name.
     *
     * @param $entity
     * @param $routeName
     * @param bool $absolute
     * @return mixed
     */
    public function generateEntityUrl($entity,$routeName,$absolute=false)
    {
        if(!is_object($entity))
        {
            throw new InvalidArgumentException('Entity parameter shoud be a valid object.');
        }

        if(!is_string($routeName))
        {
            throw new InvalidArgumentException('Route name parameter shoud be a string.');
        }

        $collection     = $this->router->getRouteCollection();
        $route          = $collection->get($routeName);

        if(!is_object($route))
        {
            throw new InvalidArgumentException(sprintf(
                'Route %s is not defined.',
                $routeName
            ));
        }

        $compiledRoute  = $route->compile();
        $variables      = $compiledRoute->getVariables();
        $parameters     = array();

        // build up the parameters array needed for route generation
        // try to get the routes variables from the entities properties
        foreach($variables as $property)
        {
            if(method_exists($entity,'get'.ucfirst($property)))
            {
                // first step, try to call the get<PROPERTY> method
                $parameters[$property]=$entity->{'get'.ucfirst($property)}();
                continue;
            }

            // if no getter exists, use reflection to obtain the properties value
            $reflectionClass = new \ReflectionClass($entity);
            $reflectionProperty = $reflectionClass->getProperty($property);

            if(!$reflectionProperty)
            {
                throw new PropertyNotFoundException(sprintf(
                    'Property %s of class %s neither accessible through getter %s nor through property.',
                    $property,
                    get_class($entity),
                    'get'.ucfirst($property)
                ));
            }

            if(!$reflectionProperty->isPublic())
            {
                // if property is not public we cheat
                $reflectionProperty->setAccessible(true);
            }

            $parameters[$property]=$reflectionProperty->getValue($entity);
        }

        return $this->router->generate($routeName,$parameters,$absolute);

    }

    public function getFilters()
    {
        return array(
            $this->prefix.'entity_url'  => new \Twig_Filter_Method($this,'generateEntityUrl'),
        );
    }

    public function getName()
    {
        return 'room13_twig_entity_route_extension';
    }
}
