<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../../vendor/autoload.php');

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Service\GeocodingService;
use PHPFacile\Geocoding\Db\Service\LocationService;
use PHPFacile\Openstreetmap\Service\OpenstreetmapService;
use Zend\Db\Adapter\Adapter;

$cfg = include(__DIR__.'/../../config/autoload/local.php');

$geocodingService = new GeocodingService(
    $cfg['CFC']['geocoding']['geocoders'],
    $cfg['CFC']['geocoding']['cache']['cache_dir']
);

$adapter         = new Adapter($cfg['CFC']['adapter']);
$eventService    = new EventService($adapter);
$locationService = new LocationService($adapter);

$openstreetmapService = new OpenstreetmapService();
$locationService->setOpenstreetmapService($openstreetmapService);
$eventService->setLocationService($locationService);
$cfcService      = new CfcService();
$cfcService->setEventService($eventService);
$cfcService->setGeocodingService($geocodingService);

// Retrieve POST data (JSON body)
$jsonBody        = file_get_contents('php://input');
$eventSubmission = json_decode($jsonBody);

if (property_exists($eventSubmission, 'id')) {
    // FIXME Make sure the user is allowed to update the event
    // instead of using this stupid test
    if ('/backoffice/validate-event.php' === substr($_SERVER['HTTP_REFERER'], -30)) {
        $place = $eventSubmission->event->location->place;
        $locations = $cfcService->getGeocodingService()->getPlacesByCountryAndPlaceName($place->country->name, $place->name);

        if (0 === count($locations)) {
            echo json_encode([
                'errs' => [
                    ['code' => 'NO_PLACE_MATCH'],
                ],
                'data' => [
                    'query' => [
                        'country' => $place->country->name,
                        'place'   => $place->name,
                    ]
                ]
            ]);
        } else if (property_exists($place, 'idProvider') && (null !== $place->idProvider)) {
            $foundLocation = false;
            foreach ($locations as $location) {
                // TAKE CARE Whereas $event->locationId is always a string $location->idProvider can be an integer
                if (''.$location->idProvider === $place->idProvider) {
                    $foundLocation = true;

                    $cfcService->updateAndValidateFormEventSubmission($eventSubmission, $location);

                    echo json_encode([
                        'errs'    => [
                        ],
                    ]);
                }
            }

            if (false === $foundLocation) {
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
    }
} else {
    $cfcService->saveNewFormEventSubmission($eventSubmission);

    echo json_encode([
        'errs'    => [
        ],
    ]);
}
?>
