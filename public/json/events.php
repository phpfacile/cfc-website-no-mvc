<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../../vendor/autoload.php');

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use Zend\Db\Adapter\Adapter;

$cfg = include(__DIR__.'/../../config/autoload/local.php');
// Oups... Actually $locationService is not used (but required)
// class interface to be fixed
$adapter      = new Adapter($cfg['CFC']['adapter']);
$eventService = new EventService($adapter);
$cfcService   = new CfcService();
$cfcService->setEventService($eventService);

// TODO Use cache

echo $cfcService->getEventsAsJSON();
