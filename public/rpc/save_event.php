<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../../vendor/autoload.php');

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Service\GeocodingService;
use PHPFacile\Geocoding\Db\Service\LocationService;
use Zend\Db\Adapter\Adapter;

$cfg = include(__DIR__.'/../../config/autoload/local.php');

$geocodingService = new GeocodingService(
    $cfg['CFC']['geocoding']['geocoders'],
    $cfg['CFC']['geocoding']['cache']['cache_dir']
);

$adapter         = new Adapter($cfg['CFC']['adapter']);
$locationService = new LocationService($adapter);
$eventService    = new EventService($adapter, $locationService);
$cfcService      = new CfcService();
$cfcService->setEventService($eventService);
$cfcService->setGeocodingService($geocodingService);

// Retrieve POST data (JSON body)
$jsonBody = file_get_contents('php://input');
$event    = json_decode($jsonBody);

// TODO Implement input data validity check

$locations = $geocodingService->getPlacesByCountryAndPlaceName($event->country, $event->place);

if (0 === count($locations)) {
    echo json_encode([
        'errs'    => [
            ['code' => 'NO_PLACE_MATCH'],
        ],
    ]);
} else if (property_exists($event, 'locationId') && (null !== $event->locationId)) {
    $foundLocationId = false;
    foreach ($locations as $location) {
        // TAKE CARE Whereas $event->locationId is always a string $location->idProvider can be an integer
        if (''.$location->idProvider === $event->locationId) {
            $foundLocationId = true;

            $cfcService->saveFormEvent($event, $location);

            echo json_encode([
                'errs'    => [
                ],
            ]);
        }
    }

    if (false === $foundLocationId) {
        echo json_encode([
            'errs'    => [
                ['code' => 'PLACE_ID_NOT_WITHIN_LIST'],
            ],
            'data' => ['locations' => $locations],
        ]);
    }
} else {
        echo json_encode([
            'errs'    => [
                ['code' => 'PLACE_TO_BE_SELECTED'],
            ],
            'data' => ['locations' => $locations],
        ]);
}
?>
