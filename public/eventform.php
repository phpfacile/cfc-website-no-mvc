<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/cfc.js"></script>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<div class="container">
<form id="event_form" onsubmit="return false" class="needs-validation" novalidate>
<div class="form-group row">
  <label for="event_name" class="col-sm-2 col-form-label">Nom<abbr title="requis">*</abbr></label>
  <div class="col-sm-4">
    <input type="text" id="event_name" name="name" value="<?php echo 'Test '.date('Y-m-d H:i:s');?>" class="form-control" required>
  </div>
  <div id="event_name_feedback" class="feedback col-sm-6"></div>
</div>
<div class="form-group row">
  <label for="event_date_start" class="col-sm-2 col-form-label">Date début<abbr title="requis">*</abbr></label>
  <div class="col-sm-4">
    <input type="date" id="event_date_start" name="date_start" value="2018-12-08" class="form-control" required>
  </div>
  <div id="event_date_start_feedback" class="feedback col-sm-4"></div>
</div>
<div class="form-group row">
  <label for="event_date_end" class="col-sm-2 col-form-label">Date fin</label>
  <div class="col-sm-4">
    <input type="date" id="event_date_end" name="date_end" class="form-control">
  </div>
  <div id="event_date_end_feedback" class="feedback col-sm-4"></div>
</div>
<div class="form-group row">
  <label for="event_address" class="col-sm-2 col-form-label">N° et Rue/Place</label>
  <div class="col-sm-4">
    <input type="text" id="event_address" name="address" class="form-control">
  </div>
  <div id="event_address_feedback" class="feedback col-sm-4"></div>
</div>
<div class="form-group row">
  <label for="event_place" class="col-sm-2 col-form-label">Ville<abbr title="requis">*</abbr></label>
  <div class="col-sm-4">
    <input type="text" id="event_place" name="place" class="form-control" required>
  </div>
  <div id="event_place_feedback" class="feedback col-sm-4"></div>
</div>
<div class="form-group row">
  <label for="event_country" class="col-sm-2 col-form-label">Pays<abbr title="requis">*</abbr></label>
  <div class="col-sm-4">
    <input type="text" id="event_country" name="country" class="form-control" value="France" required>
  </div>
  <div id="event_country_feedback" class="feedback col-sm-4"></div>
</div>
<div class="form-group row">
  <button onClick="saveEvent()">Enregistrer</button>
</div>
</form>
</div>
