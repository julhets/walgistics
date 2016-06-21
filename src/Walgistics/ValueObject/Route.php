<?php

namespace Walgistics\ValueObject;

use Respect\Validation\Validator as v;

/**
 * @Entity @Table(name="route")
 **/
class Route implements \JsonSerializable
{
  /** @Id
   * @Column(type="integer") *
   * @GeneratedValue
   */
  protected $id = null;

  /**
   * @Column(type="string", length=255)
   */
  protected $fromLocal;

  /**
   * @Column(type="string", length=255)
   */
  protected $toLocal;

  /**
   * @Column(type="float")
   */
  protected $distance;

  /**
   * @ManyToOne(targetEntity="RouteMap", inversedBy="routes")
   * @JoinColumn(name="routemap_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $routeMap;

  public function bind(array $data)
  {
    $this->id = isset($data['id']) ? $data['id'] : null;
  }

  public function __clone()
  {
  }

  /**
   * @return mixed
   */
  public function getRouteMap()
  {
    return $this->routeMap;
  }

  /**
   * @param mixed $routeMap
   */
  public function setRouteMap($routeMap)
  {
    $this->routeMap = $routeMap;
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
  public function getToLocal()
  {
    return $this->toLocal;
  }

  /**
   * @param mixed $toLocal
   */
  public function setToLocal($toLocal)
  {
    $this->toLocal = $toLocal;
  }

  /**
   * @return mixed
   */
  public function getFromLocal()
  {
    return $this->fromLocal;
  }

  /**
   * @param mixed $fromLocal
   */
  public function setFromLocal($fromLocal)
  {
    $this->fromLocal = $fromLocal;
  }

  /**
   * @return mixed
   */
  public function getDistance()
  {
    return $this->distance;
  }

  /**
   * @param mixed $distance
   */
  public function setDistance($distance)
  {
    $this->distance = $distance;
  }

  public function assert()
  {
    return v::numeric()
        ->assert($this->getId());
  }

  public function jsonSerialize()
  {
    $properties = get_object_vars($this);
    $properties['id'] = $this->getId();

    return $properties;
  }
}
