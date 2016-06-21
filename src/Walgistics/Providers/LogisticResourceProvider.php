<?php

namespace Walgistics\Providers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Walgistics\Resources\LogisticResource;
use Walgistics\Repository\LogisticRepository;

class LogisticResourceProvider extends CommonResourceProvider implements
    ServiceProviderInterface,
    ControllerProviderInterface
{

  /**
   * @param Application $app
   */
  public function register(Application $app)
  {
    $app['repository.logistic'] = $app->share(function (Application $app) {
      return new LogisticRepository($app['orm.em']);
    });

    $app['resources.logistic'] = $app->share(function (Application $app) {
      return new LogisticResource(
          $app['repository.logistic']
      );
    });
  }

  /**
   * @param Application $app
   * @return mixed
   */
  public function connect(Application $app)
  {
    $resources = $app['controllers_factory'];

    //GET /map
    $resources->get('/map', 'resources.logistic:welcome');
    $resources->get('/map/', 'resources.logistic:welcome');

    //POST /map
    $resources->post('/map', 'resources.logistic:persistRouteMap');
    $resources->post('/map/', 'resources.logistic:persistRouteMap');

    //POST /map/route
    $resources->post('/map/route', 'resources.logistic:getRoute');
    return $resources;
  }
}
