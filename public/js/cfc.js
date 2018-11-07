function saveEvent()
{
    var name = $('#event_name').val().trim();
    var dateStart = $('#event_date_start').val().trim();
    var dateEnd = $('#event_date_end').val().trim();
    var address = $('#event_address').val().trim();
    var place = $('#event_place').val().trim();
    var country = $('#event_country').val().trim();
    var locationId = null;
    if ($('#event_location_id').length > 0) {
        locationId = $('#event_location_id').val().trim();
    }

    console.log('Name=['+name+']');
    console.log('Date (start)=['+dateStart+']');
    console.log('Date (end)=['+dateEnd+']');
    console.log('address=['+address+']');
    console.log('place=['+place+']');
    console.log('country=['+country+']');
    console.log('locationId=['+locationId+']');

    // TODO Client side validation to be completed
    var feedback = $('#event_name_feedback');
    if (5 > name.length) {
        $(feedback).html('5 caractères minimum requis');
        //$(feedback).removeClass('valid-feedback');
        //$(feedback).addClass('invalid-feedback');
        $(feedback).css('color', 'red');
    } else {
        $(feedback).html('');
    }

    $('#event_form').removeClass('needs-validation');
    $('#event_form').addClass('was-validated');

    var event = {};
    event.name = name;
    event.dateStart = dateStart;
    event.dateEnd = dateEnd;
    event.address = address;
    event.place = place;
    event.country = country;
    if (null != locationId) {
        event.locationId = locationId;
    }

    // FIXME Replace URL with URL in a MVC context (usually with no .php extension)
    $.ajax({
        url: '/rpc/save_event.php',
        method: 'POST',
        data : JSON.stringify(event),
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
            // TODO Manage success
            location.reload();
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        alert(textStatus);
    });
}
