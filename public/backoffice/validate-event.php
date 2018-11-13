<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../../vendor/autoload.php');

// TODO Authentication + Access rights check

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Db\Service\LocationService;

use Zend\Db\Adapter\Adapter;

$cfg = include(__DIR__.'/../../config/autoload/local.php');

$adapter         = new Adapter($cfg['CFC']['adapter']);
$eventService    = new EventService($adapter);
$locationService = new LocationService($adapter);
$eventService->setLocationService($locationService);
$cfcService      = new CfcService();
$cfcService->setEventService($eventService);


$eventSubmission = $cfcService->getNextFormEventSubmissionToBeValidated();

if (null === $eventSubmission) {
    echo 'No more event to process';
    die();
}

//var_dump($eventSubmission);

require_once(__DIR__.'/../../view/event_form.phtml');

require_once(__DIR__.'/../../view/partials/event_validation_buttons_bar.phtml');
?>
