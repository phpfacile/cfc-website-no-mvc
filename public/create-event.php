<?php
// TODO Please... Please... give me a controller to centralize initialisation
require_once(__DIR__.'/../vendor/autoload.php');

$submitter = new \StdClass();
$submitter->name = 'Test User';
$submitter->email = 'test.user@gmail.com';

$event = new \StdClass();
$event->name = 'My Event created at '.date('Y-m-d H:i:s').' !';
$event->url = 'http://www.mywebsite.com';
$event->type = 'evt';
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


require_once(__DIR__.'/../view/event_form.phtml');

require_once(__DIR__.'/../view/partials/event_new_buttons_bar.phtml');
