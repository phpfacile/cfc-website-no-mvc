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

$eventSubmissions = $cfcService->getNextFormEventsSubmissionToBeValidated();

if (0 === count($eventSubmissions)) {
    echo 'No more event to process';
    die();
}
?>
<table>
  <tr>
    <th>Proposé le</th>
    <th>Par</th>
  </tr>
<?php
foreach ($eventSubmissions as $eventSubmission) {
?>
  <tr>
    <td><?= $eventSubmission->insertionDateTimeUTC;?></td>
    <td><?php echo $escaper->escapeHtml($eventSubmission->submitter->name);?></td>
  </tr>
<?php
}
?>
</table>
