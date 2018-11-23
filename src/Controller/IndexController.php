<?php
// TODO Use a namespace

class IndexController
{
    protected $eventTypes;

    public function __construct($eventTypes)
    {
        $this->eventTypes = $eventTypes;
    }

    public function createEventAction()
    {
        $submitter = new \StdClass();
        $submitter->name = 'Test User';
        $submitter->email = 'test.user@gmail.com';

        $event = new \StdClass();
        $event->name = 'My Event created at '.date('Y-m-d H:i:s').' !';
        $event->url = 'http://www.mywebsite.com';
        $event->type = $this->eventTypes[0];
        $event->dateStart = '2018-12-08';
        $event->location = new \StdClass();
        $event->location->place = new \StdClass();
        $event->location->place = new \StdClass();
        $event->location->place->name = 'Fécamp';
        $event->location->place->country = new \StdClass();
        $event->location->place->country->code = 'FR';

        $eventSubmission = new \StdClass();

        // TODO Get actual locale
        $eventSubmission->locale    = 'fr';
        $eventSubmission->submitter = $submitter;
        $eventSubmission->event     = $event;

        $form = new \StdClass();
        $form->eventTypes = $this->eventTypes;

        require_once(__DIR__.'/../view/event_form.phtml');

        require_once(__DIR__.'/../view/partials/event_new_buttons_bar.phtml');
    }
}
