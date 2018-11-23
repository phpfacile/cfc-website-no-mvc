function saveEvent()
{
    var locale = $('#locale').val().trim();
    var userName = $('#user_name').val().trim();
    var userEmail = $('#user_email').val().trim();
    var eventName = $('#event_name').val().trim();
    var eventUrl = $('#event_url').val().trim();
    var eventType = $('#event_type').val().trim();
    var dateStart = $('#event_date_start').val().trim();
    var dateEnd = $('#event_date_end').val().trim();
    //var address = $('#event_address').val().trim();
    var eventPlace = $('#event_place').val().trim();
    var countryName = $('#event_country option:selected').text().trim();
    var countryCode = $('#event_country').val().trim();

    var eventSubmissionId = null;
    if ($('#event_submission_id').length > 0) {
        eventSubmissionId = $('#event_submission_id').val().trim();
    }
    var locationId = null;
    if ($('#event_location_id').length > 0) {
        locationId = $('#event_location_id').val().trim();
    }

    console.log('Locale=['+locale+']');
    console.log('User name=['+userName+']');
    console.log('User email=['+userEmail+']');
    console.log('Event name=['+eventName+']');
    console.log('Event url=['+eventUrl+']');
    console.log('Event type=['+eventType+']');
    console.log('Event Date (start)=['+dateStart+']');
    console.log('Event Date (end)=['+dateEnd+']');
    //console.log('address=['+address+']');
    console.log('Event place=['+eventPlace+']');
    console.log('Event country Name=['+countryName+']');
    console.log('Event country Code=['+countryCode+']');
    console.log('eventSubmissionId=['+eventSubmissionId+']');
    console.log('locationId=['+locationId+']');

    // TODO Client side validation to be completed
    var feedback = $('#event_name_feedback');
    if (5 > eventName.length) {
        $(feedback).html('5 caractères minimum requis');
        //$(feedback).removeClass('valid-feedback');
        //$(feedback).addClass('invalid-feedback');
        $(feedback).css('color', 'red');
    } else {
        $(feedback).html('');
    }

    $('#event_form').removeClass('needs-validation');
    $('#event_form').addClass('was-validated');

    var eventSubmission = {};

    eventSubmission.locale = locale;
    if (null != eventSubmissionId) {
        eventSubmission.id = eventSubmissionId;
    }

    var submitter = {};
    submitter.name = userName;
    submitter.email = userEmail;
    eventSubmission.submitter = submitter;

    var event = {};
    event.name = eventName;
    event.dateStart = dateStart;
    event.dateEnd = dateEnd;
    event.url = eventUrl;
    event.type = eventType;
    event.location = {};
    //event.location.address = address;
    event.location.place = {};
    event.location.place.name = eventPlace;

    var country = {};
    country.name = countryName;
    country.code = countryCode;
    event.location.place.country = country;
    if (null != locationId) {
        event.location.place.idProvider = locationId;
    }
    eventSubmission.event = event;

    // FIXME Replace URL with URL in a MVC context (usually with no .php extension)
    $.ajax({
        url: '/rpc/save-event',
        method: 'POST',
        data : JSON.stringify(eventSubmission),
        dataType: "json",
    })
    .done(function(response) {
        console.log(response);
        console.log(response.errs);
        console.log(response.errs.length);
        if (response.errs.length > 0) {
            $(response.errs).each(function(idx, err) {
                var err = response.errs[0];
                switch (err.code) {
                    case 'PLACE_TO_BE_SELECTED':
                        $('#event_place').attr('readonly', true);
                        $('#event_country').attr('readonly', true);
                        //$('#place').hide();
                        var selectHTML = '<option value="">(sélectionner)</option>';
                        $(response.data.locations).each(function(idx, location) {
                            selectHTML += '<option value="'+location.idProvider+'">'+location.name+'</option>';
                        });
                        console.log($('#event_location_id').length);
                        if (0 == $('#event_location_id').length) {
                            selectHTML = '<select id="event_location_id" name="location_id" class="form-control">' + selectHTML +  '</select><br>';
                            $('#event_place').before(selectHTML);
                        } else {
                            $('#event_location_id').html(selectHTML);
                        }
                    default:
                        // ignore
                }
            });
            // FIXME loop instead of take only 1st err
        } else {
            if (null != eventSubmissionId) {
                location.reload();
            } else {
                // TODO Manage success
                $('body').html('Thanks !');
            }
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        alert(textStatus);
    });
}
