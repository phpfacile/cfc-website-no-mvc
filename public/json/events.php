<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../../vendor/autoload.php');

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Db\Service\LocationService;
use Zend\Db\Adapter\Adapter;

$cfg = include(__DIR__.'/../../config/autoload/local.php');
// Oups... Actually $locationService is not used (but required)
// class interface to be fixed
$adapter         = new Adapter($cfg['CFC']['adapter']);
$locationService = new LocationService($adapter);
$eventService    = new EventService($adapter, $locationService);
$cfcService     = new CfcService();
$cfcService->setEventService($eventService);

// TODO Use cache

echo $cfcService->getEventsAsJSON();
