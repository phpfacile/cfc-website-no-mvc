<?php
// Maybe we should use an "official" routing library

// The following is really basic and can be use only as long as there is no parameter
// passed in the URI
switch ($_SERVER['REQUEST_URI']) {
    case '/create-event':
        $class = 'IndexController';
        $action = 'createEventAction';
        break;
    case '/json/events':
        $class = 'RPCController';
        $action = 'listEvents';
        break;
    case '/rpc/save-event':
        $class = 'RPCController';
        $action = 'saveEventAction';
        break;
    case '/backoffice/validate-event':
        $class = 'AdminController';
        $action = 'getNextEventToBeValidatedAction';
        break;
    case '/backoffice/events-to-be-validated':
        $class = 'AdminController';
        $action = 'listEventsToBeValidatedAction';
        break;
    case '/login':
        $class = 'LoginController';
        $action = 'loginAction';
        break;
    case '/logout':
        $class = 'LoginController';
        $action = 'logoutAction';
        break;
    case '/':
    case '':
        die('Welcome');
    default:
        header("HTTP/1.0 404 Not Found");
        die('Page not found');
}

require_once(__DIR__.'/../vendor/autoload.php');

// Here we are sure that there is no user input with $class and $action
// otherwise this would be really anoying
require_once(__DIR__.'/../src/Controller/'.$class.'.php');

use CFC\Service\CfcService;

use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Service\GeocodingService;
use PHPFacile\Geocoding\Db\Service\LocationService;
use PHPFacile\Openstreetmap\Service\OpenstreetmapService;

use Zend\Db\Adapter\Adapter;

$controllerParam2 = null;
switch ($_SERVER['REQUEST_URI']) {
    case '/login':
    case '/logout':
        $cfg = include(__DIR__.'/../config/autoload/local.php');
        // Actually constructor parameter is not a $cfcService
        $cfcService = $cfg['CFC']['users'];
        break;
    case '/create-event':
        $cfg = include(__DIR__.'/../config/autoload/local.php');
        // Actually constructor parameter is not a $cfcService
        $cfcService = $cfg['CFC']['events']['types'];
        break;
    case '/backoffice/validate-event':
        $cfg = include(__DIR__.'/../config/autoload/local.php');
        $controllerParam2 = $cfg['CFC']['events']['types'];
        // continue
    case '/json/events':
    case '/backoffice/events-to-be-validated':
    case '/rpc/save-event':
        $cfg = include(__DIR__.'/../config/autoload/local.php');

        $adapter      = new Adapter($cfg['CFC']['adapter']);
        $eventService = new EventService($adapter);
        $cfcService   = new CfcService();
        $cfcService->setEventService($eventService);
        break;
}

switch ($_SERVER['REQUEST_URI']) {
    case '/backoffice/validate-event':
    case '/backoffice/events-to-be-validated':
    case '/rpc/save-event':
        $locationService = new LocationService($adapter);
        $eventService->setLocationService($locationService);
        break;
}

switch ($_SERVER['REQUEST_URI']) {
    case '/rpc/save-event':
        $openstreetmapService = new OpenstreetmapService();
        $locationService->setOpenstreetmapService($openstreetmapService);
        $geocodingService = new GeocodingService(
            $cfg['CFC']['geocoding']['geocoders'],
            $cfg['CFC']['geocoding']['cache']['cache_dir']
        );
        $cfcService->setGeocodingService($geocodingService);
        break;
}
$controller = new $class($cfcService, $controllerParam2);

$controller->$action();
