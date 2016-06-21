<?php

namespace Walgistics\Resources;

use Respect\Validation\Exceptions\ValidationException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Walgistics\Repository\LogisticRepositoryInterface;
use Walgistics\ValueObject\RouteMap;
use Walgistics\ValueObject\Route;

class LogisticResource
{
  /**
   * @var LogisticRepository
   */
  private $repository;
  public $arrayAux;

  public function __construct(
      LogisticRepositoryInterface $repository
  )
  {
    $this->repository = $repository;
  }

  public function persistRouteMap(
      Application $app,
      Request $request
  )
  {
    try {
      $data = json_decode($request->getContent());
      $routeMap = new RouteMap();
      $routeMap->bind($data);
      $routeMap->assert();
      $routeMapExists = $this->repository->findOneRouteMapByName($routeMap->getName());
      if ($routeMapExists) {
        throw new ValidationException();
      }
      $routeMapId = $this->repository->persistRouteMap($routeMap);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500, $exception->getMessage());
    }

    return $app->json(['id' => $routeMapId], 201);
  }

  public function deleteRouteMap(Application $app,
                                 Request $request,
                                 $id)
  {
    try {
      $routeMap = $this->repository->findOne($id);
      if (!$routeMap) {
        //route not founded
        throw new ValidationException('Route not founded.');
      }
      $this->repository->remove($id);
      exit;
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }

  public function getRoute(Application $app,
                           Request $request)
  {
    try {
      $data = json_decode($request->getContent());
      $routeMap = $this->repository->findOneRouteMapByName($data->mapName);
      $from = $data->from;
      $to = $data->to;
      $autonomy = $data->autonomy;
      $fuelPrice = $data->fuelPrice;

      $bestRoute = $this->getBestRoute($from, $to, $routeMap);

      //caso não exista rota disponível
      if(!$bestRoute) {
        throw new ValidationException('There is no routes available for this trip.');
      }

      $totalCostValue = null;

      $routePath = '';
      foreach ($bestRoute as $routePath => $routeDistance) {
        $totalCostValue = ($routeDistance / $autonomy) * $fuelPrice;
      }

      $responseObject = array('totalCostValue' => $totalCostValue, 'route' => $routePath);
      return $app->json($responseObject);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500, $exception->getMessage());
    }
  }

  private function getBestRoute($from, $to, $routeMap)
  {
    $routes = $routeMap->getRoutes();
    $routesMatriz = array();
    foreach ($routes as $route) {
      $routesMatriz[$route->getFromLocal()][$route->getToLocal()] = $route->getDistance();
    }

    //retorna todas as rotas possíveis para o $from -> $to através de uma função recursiva
    $bestRoute = $this->getAllRoutes($from, $to, $routesMatriz);
    asort($bestRoute);
    //retorna apenas o primeiro elemento após a ordenação ascendente, ou seja, a rota de menor kilometragem
    return array_splice($bestRoute, 0, 1);
  }

  private function getAllRoutes($from, $to, $routesMatriz, $breadcrumb = '', $totalDistance = 0)
  {
    $originBreadcrumb = $breadcrumb;
    $originalTotalDistance = $totalDistance;
    foreach ($routesMatriz[$from] as $key => $route) {
      if ($key != $to) {
        $breadcrumb = $breadcrumb . $from;
        $totalDistance = $totalDistance + $route;
        $this->getAllroutes($key, $to, $routesMatriz, $breadcrumb, $totalDistance);
      } else {
        $breadcrumb .= $from . $to;
        $totalDistance += $route;
        $this->arrayAux[$breadcrumb] = $totalDistance;
      }

      $breadcrumb = $originBreadcrumb;
      $totalDistance = $originalTotalDistance;
    }
    return $this->arrayAux;
  }
}
