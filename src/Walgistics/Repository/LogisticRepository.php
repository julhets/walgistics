<?php

namespace Walgistics\Repository;

use Walgistics\ValueObject\RouteMap;

class LogisticRepository implements LogisticRepositoryInterface
{
  use RepositoryPaginable;

  private $em;

  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
    $this->em = $em;
  }

  public function findOneRouteMap($id)
  {
    return $this->em->find('\Walgistics\ValueObject\RouteMap', $id);
  }

  public function findOneRouteMapByName($name)
  {
    return $this->em->getRepository('\Walgistics\ValueObject\RouteMap')->findOneBy(array('name' => $name));
  }

  public function findAllRoutesByFromLocalAndRouteMap($fromLocal, $routeMapId)
  {
    return $this->em->getRepository('\Walgistics\ValueObject\Route')->findBy(array('fromLocal' => $fromLocal, 'routeMap' => $routeMapId));
  }

  public function persistRouteMap(RouteMap $routeMap)
  {
    try {
      $this->em->persist($routeMap);
      $this->em->flush();
    } catch (\Exception $exception) {
      throw $exception;
    }

    return $routeMap->getId();
  }

  public function removeRouteMap($id)
  {
    try {
      $this->em->remove($this->findOne($id));
      $this->em->flush();
      return $id;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }
}
