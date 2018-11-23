<?php
use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Service\GeocodingService;
use PHPFacile\Geocoding\Db\Service\LocationService;
use PHPFacile\Openstreetmap\Service\OpenstreetmapService;
use Zend\Db\Adapter\Adapter;

class RPCController
{
    protected $cfcService;
    protected $loggedUser;

    public function __construct($cfcService)
    {
        session_start();
        $this->cfcService = $cfcService;

        // No need to be authenticated :-/ Except for event validation
        // TODO Authentication + Access rights check
        if (false === array_key_exists('userLogin', $_SESSION)) {
            $this->loggedUser = null;
        } else {
            // TODO Use a "real" class
            $user = new \StdClass();
            $user->login = $_SESSION['userLogin'];

            $this->loggedUser = $user;
        }
    }

    public function saveEventAction()
    {
        // Retrieve POST data (JSON body)
        $jsonBody        = file_get_contents('php://input');
        $eventSubmission = json_decode($jsonBody);

        if (property_exists($eventSubmission, 'id')) {
            // Make sure the user is allowed to update the event
            if (null !== $this->loggedUser) {
                $place = $eventSubmission->event->location->place;
                $locations = $this->cfcService->getGeocodingService()->getPlacesByCountryAndPlaceName($place->country->name, $place->name);

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

                            $this->cfcService->updateAndValidateFormEventSubmission($eventSubmission, $location, $this->loggedUser);

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
            $this->cfcService->saveNewFormEventSubmission($eventSubmission);

            echo json_encode([
                'errs'    => [
                ],
            ]);
        }
    }

    public function listEvents()
    {
        echo $this->cfcService->getEventsAsJSON();
    }
}
