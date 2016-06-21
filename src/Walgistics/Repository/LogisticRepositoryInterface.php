<?php

namespace Walgistics\Repository;

use Walgistics\ValueObject\RouteMap;

interface LogisticRepositoryInterface
{
  public function findOneRouteMap($id);

  public function persistRouteMap(RouteMap $routeMap);

  public function removeRouteMap($id);
}
