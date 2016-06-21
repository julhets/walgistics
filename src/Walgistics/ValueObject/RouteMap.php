<?php

namespace Walgistics\ValueObject;

use Respect\Validation\Validator as v;

/**
 * @Entity @Table(name="route_map")
 **/
class RouteMap implements \JsonSerializable
{
  /** @Id
   * @Column(type="integer") *
   * @GeneratedValue
   */
  protected $id = null;
  /**
   * @Column(type="string", length=255)
   */
  protected $name;
  /**
   * @OneToMany(targetEntity="Route", mappedBy="routeMap", cascade="persist")
   */
  protected $routes;

  public function __construct()
  {
    $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public function bind($data)
  {
    $this->name = $data->name;

    $routes = array();
    foreach ($data->routes as $key => $item) {
      $route = new Route();
      $route->setFromLocal($item[0]);
      $route->setToLocal($item[1]);
      $route->setDistance($item[2]);
      $route->setRouteMap($this);

      $routes[] = $route;
    }
    $this->setRoutes($routes);
  }

  public function __clone()
  {
  }

  /**
   * @return mixed
   */
  public function getRoutes()
  {
    return $this->routes;
  }

  /**
   * @param mixed $routes
   */
  public function setRoutes($routes)
  {
    $this->routes = $routes;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  public function assert()
  {
    return v::string()
        ->assert($this->getName());
  }

  public function jsonSerialize()
  {
    $properties = get_object_vars($this);
    $properties['id'] = $this->getId();

    return $properties;
  }
}
