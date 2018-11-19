<?php
// TODO Authentication + Access rights check

use CFC\Service\CfcService;
use PHPFacile\Event\Db\Service\EventService;
use PHPFacile\Geocoding\Db\Service\LocationService;

use Zend\Db\Adapter\Adapter;

$escaper = new Zend\Escaper\Escaper('utf-8');

$cfg = include(__DIR__.'/../../config/autoload/local.php');

$adapter         = new Adapter($cfg['CFC']['adapter']);
$eventService    = new EventService($adapter);
$locationService = new LocationService($adapter);
$eventService->setLocationService($locationService);
$cfcService      = new CfcService();
$cfcService->setEventService($eventService);

class AdminController
{
    protected $cfcService;

    public function __construct($cfcService)
    {
        $this->cfcService = $cfcService;
        // TODO Authentication + Access rights check
    }

    public function getNextEventToBeValidatedAction()
    {
        $eventSubmission = $this->cfcService->getNextFormEventSubmissionToBeValidated();

        if (null === $eventSubmission) {
            echo 'No more event to process';
            die();
        }
        require_once(__DIR__.'/../view/event_form.phtml');

        require_once(__DIR__.'/../view/partials/event_validation_buttons_bar.phtml');
    }

    public function listEventsToBeValidatedAction()
    {
        $eventSubmissions = $this->cfcService->getNextFormEventsSubmissionToBeValidated();

        if (0 === count($eventSubmissions)) {
            echo 'No more event to process';
            die();
        }
        ?>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <table class="table">
          <tr>
            <th>Proposé le</th>
            <th>Par</th>
            <th>Email</th>
            <th>Evénement</th>
            <th>Type</th>
            <th>URL</th>
            <th>Ville</th>
            <th>Pays</th>
          </tr>
        <?php
        foreach ($eventSubmissions as $eventSubmission) {
        ?>
          <tr>
            <td><?= $eventSubmission->submissionDateTimeUTC;?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->submitter->name);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->submitter->email);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->event->name);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->event->type);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->event->url);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->event->place->name);?></td>
            <td><?php echo $escaper->escapeHtml($eventSubmission->event->place->country->code);?></td>
          </tr>
        <?php
        }
        ?>
        </table>
<?php
    }
}
