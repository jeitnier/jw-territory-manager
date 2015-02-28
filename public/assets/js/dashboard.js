
$(function() {
    $('[data-toggle="offcanvas"]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });
});

// auto-select first visible input of modal
$('.modal').on('shown.bs.modal', function() {
    $('form :input:not(input[type=hidden], button):first').focus();
});

// select-all/deselect-all
$('#select-all').on('change', function() {
    $('input:checkbox').prop('checked', $(this).prop('checked'));
});

// remove margin on checkbox label parents
$('input:checkbox').parent().css('margin-bottom', 0);


// PUBLISHERS
// publishers search (index)
$('#publishers-search').on('input', function() {
    var input = $(this).val();

    $.get('/admin/publishers/search', { input: input }, function(response) {
        $('#publishers-list').html(response);
    });
});

// delete publisher
$('#publishers .delete').on('click', function(e) {
    e.preventDefault();

    var route  = '/admin/publishers';
    var params = {
        id:          $(this).closest('tr').data('id'),
        route:       route,
        redirect:    route,
        title:       'Delete Publisher ' + $(this).closest('tr').data('name') + '?',
        body:        'Are you sure you want to delete publisher &lt;strong&gt;' + $(this).closest('tr').data('name') + '&lt;/strong&gt;?',
        button_text: 'Delete Publisher'
    };

    ajaxDelete(params);
});

$('#publishers #select-all').on('change', function() {
    if ($(this).prop('checked')) {
        $('#publishers-bulk-delete').removeAttr('disabled').removeClass('btn-default').addClass('btn-warning');
    } else {
        $('#publishers-bulk-delete').attr('disabled', 'disabled').removeClass('btn-warning').addClass('btn-default');
    }
});

// bulk delete publishers
$('#publishers-bulk-delete').on('click', function(e) {
    e.preventDefault();

    var route      = '/admin/publishers';
    var ids        = $('#publishers tbody tr').map(function() {
        return $(this).data('id');
    }).get();
    var publishers = $('#publishers tbody tr').map(function() {
        return $(this).data('name');
    }).get();
    var params     = {
        id:          ids.join('|'),
        route:       route,
        redirect:    route,
        title:       'Delete Publishers?',
        body:        'Are you sure you want to delete publishers &lt;strong&gt;' + publishers.join(', ') + '&lt;strong&gt;?',
        button_text: 'Delete Publishers'
    };

    ajaxDelete(params);
});


// TERRITORIES
// territories search (index)
$('#territories-search').on('input', function() {
    var input = $(this).val();

    $.get('/admin/territories/search', { input: input }, function(response) {
        $('#territories-list').html(response);
    });
});

$('#territories #publisher-id').on('change', function(e) {
    e.preventDefault();

    showPublisherTerritories();
});

$('#territories #territory-id').on('change', function(e) {
    e.preventDefault();

    showTerritoryInfo();
});

function showPublisherTerritories() {
    var publisher_id = $('#territories #publisher-id').val();

    // clear out any existing html
    $('.territories-signed-out').html('').next().html('');

    $.get('/admin/territories/' + publisher_id + '/show-publisher-territories', function(response) {
        var tabs, panes;
        tabs = panes = '';

        if ($('input[type="hidden"]#territory-id').length) {
            $('input[type="hidden"]#territory-id').val(response[0].territory_id);
        }

        $.each(response, function(i, d) {
            tabs  += '<li' + (i == 0 ? ' class="active"' : '') + '>';
            tabs  += '<a href="#signed-out-' + i + '" data-toggle="tab" data-territory-id="' + d.territory_id + '">' + d.label + '</a>';
            tabs  += '</li>';

            panes += '<div class="tab-pane' + (i == 0 ? ' active"' : '') + '" id="signed-out-' + i + '">';
            panes += '<hr>';
            panes += '<div class="row">';
            panes += '<div class="col-xs-6">';
            panes += '<h5>Signed Out</h5>';
            panes += d.sign_out_date;
            panes += '</div>';
            panes += '<div class="col-xs-6">';
            panes += '<h5>Due Date</h5>';
            panes += d.due_date;
            panes += '</div>';
            panes += '<div class="col-xs-6">';
            panes += '<h5>Type</h5>';
            panes += d.type;
            panes += '</div>';
            panes += '<div class="col-xs-6">';
            panes += '<h5>Area Type</h5>';
            panes += d.area_type;
            panes += '</div>';
            panes += '<div class="col-xs-6">';
            panes += '<h5>Map</h5>';
            panes += '</div>';
            panes += '</div>';
            panes += '</div>';
        });

        $('#territories .nav').html(tabs);
        $('#territories .tab-content').html(panes);
    }).done(function() {
        $('a[data-toggle="tab"]:first').trigger('shown.bs.tab'); // trigger first tab on load
    });
}

function showTerritoryInfo() {
    var territory_id = $('#territories #territory-id').val();

    $.getJSON('/admin/territories/' + territory_id + '/show-territory-info', function(response) {
        $('#type').val(response.type);
        $('#area-type').val(response.area_type);
        $('#last-worked').val(response.last_worked);
        $('#status').val(response.status);
    });
}

// delete territory
$('#territories-list .delete').on('click', function(e) {
    e.preventDefault();

    var route  = '/admin/territories';
    var params = {
        id:          $(this).closest('tr').data('id'),
        route:       route,
        redirect:    route,
        title:       'Delete Territory ' + $(this).closest('tr').data('name') + '?',
        body:        'Are you sure you want to delete territory &lt;strong&gt;' + $(this).closest('tr').data('name') + '&lt;strong&gt;?',
        button_text: 'Delete Territory'
    };

    ajaxDelete(params);
});

$('#territories #select-all').on('change', function() {
    if ($(this).prop('checked')) {
        $('#territories-bulk-delete').removeAttr('disabled').removeClass('btn-default').addClass('btn-warning');
    } else {
        $('#territories-bulk-delete').attr('disabled', 'disabled').removeClass('btn-warning').addClass('btn-default');
    }
});

// bulk delete territories
$('#territories-bulk-delete').on('click', function(e) {
    e.preventDefault();

    var route       = '/admin/territories';
    var ids         = $('#territories tbody tr').map(function() {
        return $(this).data('id');
    }).get();
    var territories = $('#territories tbody tr').map(function() {
        return $(this).data('name');
    }).get();
    var params      = {
        id:          ids.join('|'),
        route:       route,
        redirect:    route,
        title:       'Delete Territories?',
        body:        'Are you sure you want to delete territories &lt;strong&gt;' + territories.join(', ') + '&lt;strong&gt;?',
        button_text: 'Delete Territories'
    };

    ajaxDelete(params);
});


// DO NOT CALLS
// delete do not call
$('#do-not-calls .delete').on('click', function(e) {
    e.preventDefault();

    var route  = '/admin/do-not-calls';
    var params = {
        id:          $(this).closest('tr').data('id'),
        route:       route,
        redirect:    route,
        title:       'Delete Do Not Call @ ' + $(this).closest('tr').data('name') + '?',
        body:        'Are you sure you want to delete Do Not Call @ &lt;strong&gt;' + $(this).closest('tr').data('name') + '&lt;strong&gt;?',
        button_text: 'Delete Do Not Call'
    };

    ajaxDelete(params);
});

$('#do-not-calls #select-all').on('change', function() {
    if ($(this).prop('checked')) {
        $('#do-not-calls-bulk-delete').removeAttr('disabled').removeClass('btn-default').addClass('btn-warning');
    } else {
        $('#do-not-calls-bulk-delete').attr('disabled', 'disabled').removeClass('btn-warning').addClass('btn-default');
    }
});

// bulk delete territories
$('#do-not-calls-bulk-delete').on('click', function(e) {
    e.preventDefault();

    var route        = '/admin/do-not-calls';
    var ids          = $('#do-not-calls tbody tr').map(function() {
        return $(this).data('id');
    }).get();
    var do_not_calls = $('#do-not-calls tbody tr').map(function() {
        return $(this).data('name');
    }).get();
    var params       = {
        id:          ids.join('|'),
        route:       route,
        redirect:    route,
        title:       'Delete Do Not Calls?',
        body:        'Are you sure you want to delete Do Not Calls @ &lt;strong&gt;' + do_not_calls.join(', ') + '&lt;strong&gt;?',
        button_text: 'Delete Do Not Calls'
    };

    ajaxDelete(params);
});


// SIGN IN/OUT TERRITORIES
// sign out
$('#territories select#territory-id').on('change', function() {
    var territory_id = $(this).val();

    $.getJSON('/ajax/map_embed_id', { territory_id: territory_id }, function(response) {
        $('#map-0').remove();
        createMap($('#map-container'), 0, response.map_embed_id);
    });
});

// sign out - email card modal
$('#email-modal #submit').on('click', function(e) {
    e.preventDefault();

    var email        = $('#email').val();
    var territory_id = $(this).data('territory-id');
    var redirect     = $('#email-button').data('redirect');
    var submit       = $('#submit');

    $('#submit').html('<i class="fa fa-spinner fa-spin"></i> Sending...');

    $.ajax({
        type:    'POST',
        url:     '/admin/territories/sign-out/' + territory_id + '/email',
        data:    { email: email },
        success: function(response) {
            $('#email-modal').modal('hide');

            if (response.status) {
                showAlertBox(response, 'alert-success');
                setTimeout(function() {
                    window.location.href = redirect;
                }, 3000);
            } else {
                showAlertBox(response, 'alert-danger');
            }
        }
    });
});

// sign in - update map of territory selection
$(document).on('shown.bs.tab', '#territories .territories-signed-out a[data-toggle="tab"]', function(e) {
    var target       = e.target;
    var index        = $(this).parent().index();
    var territory_id = $(this).data('territory-id');

    $('#territory-id').val(territory_id);

    $.getJSON('/ajax/map_embed_id', { territory_id: territory_id }, function(response) {
        createMap($(target).attr('href'), index, response.map_embed_id);
    });
});


// REPORTS
$('#reports #publisher-history').on('change', function() {
    toggleReportDropdowns($(this), $('#reports #publisher-id'));
});
$('#reports #territory-history').on('change', function() {
    toggleReportDropdowns($(this), $('#reports #territory-id'));
});
$('#reports #select-all').on('change', function() {
    toggleReportDropdowns($('#reports #publisher-history'), $('#reports #publisher-id'));
    toggleReportDropdowns($('#reports #territory-history'), $('#reports #territory-id'));
});
function toggleReportDropdowns($this, $select) {
    if ($this.is(':checked')) {
        $this.parent().next().find('.btn-group').removeClass('hidden');
    } else {
        // deselect all options if unchecked so it doesn't get included in reports
        $('option', $select).each(function(e) {
            $select.multiselect('deselect', $(this).val());
        });

        $this.parent().next().find('.btn-group').addClass('hidden');
    }
}


// UTILITIES
// territory resolver

/**
 * Look through all maps to find territory that includes address given.
 */
$('#utilities.territory-resolver').on('submit', function(e)
{
    e.preventDefault();

    var address = $(this).find('#address').val();

    $('#utilities.territory-resolver h3').not('#messages h3').remove();
    $('#utilities.territory-resolver #messages').removeClass('hide');

    $.post('/utility/geocode-address', { address: address }, function(response)
    {
        var geocode = $.parseJSON(response);

        if ('OK' == geocode.status)
        {
            // get list of territory maps
            getMapsList().done(function(maps)
            {
                getMapboxMap(maps, geocode);
            });
        }
    });
});

/**
 * Iterate through all MapBox maps until lat/long points match a territory.
 *
 * @param maps
 * @param geocode
 */
function getMapboxMap(maps, geocode) {

    var access_token = '';
    var map_params   = maps[0];
    var map_embed_id = map_params.map_embed_id;

    if (maps.length > 0)
        maps.shift();

    // remove any existing map element from DOM
    $('#utilities.territory-resolver h3').not($('#messages h3')).remove();
    $('#utilities.territory-resolver #map').remove();

    // re-create map element and add to DOM
    $('#utilities.territory-resolver').append('<div id="map" style="visibility: hidden;"></div>');

    $.get('/ajax/mapbox-api-key', function(response)
    {
        access_token = response;
    });

    // create mapbox object
    L.mapbox.accessToken = access_token;

    var map = L.mapbox.map('map', map_embed_id, {
        zoomAnimation: false
    });

    // create marker of address entered
    L.mapbox.featureLayer({
        type: 'Feature',
        geometry: {
            type: 'Point',
            coordinates: [
                geocode.location.lng,
                geocode.location.lat
            ]
        },
        properties: {
            title: address,
            'marker-size': 'medium',
            'marker-color': '#f44',
            'marker-symbol': 'star'
        }
    }).addTo(map);

    // create polygon layer and add to map from map's geojson
    var polygonLayer = L.mapbox.featureLayer().loadURL('https://a.tiles.mapbox.com/v4/' + map_embed_id + '/features.json?access_token=' + access_token).addTo(map);

    // after polygon layer has been added to map
    polygonLayer.on('ready', function() {

        // featureLayer.getBounds() returns the corners of the furthest-out markers,
        // and map.fitBounds() makes sure that the map contains these.
        map.fitBounds(polygonLayer.getBounds());

        // create a latLng object based on lat/lng of address entered
        var latlng = L.latLng(geocode.location.lat, geocode.location.lng);

        // create point in layer object
        var layer = leafletPip.pointInLayer(latlng, polygonLayer, true);

        if (layer.length) {
            showResolvedMap(map_params);
            return false;
        } else {
            if (maps.length > 0) {
                getMapboxMap(maps, geocode);
            } else if (maps.length == 0) {
                $('#utilities.territory-resolver #messages').addClass('hide');
                $('#utilities.territory-resolver #map').before('<h3>Address not found in any territories.</h3>');
                $('#utilities.territory-resolver #map').remove();
            }
        }
    });
}

/**
 * Get a list of territory maps from the database.
 *
 * @returns {*}
 */
function getMapsList() {
    return $.get('/utility/territories/maps-list');
}

/**
 * Show territory map where searched address lies within.
 *
 * @param map_params
 */
function showResolvedMap(map_params) {
    $('#utilities.territory-resolver #messages').addClass('hide');
    $('#utilities.territory-resolver #map')
        .css('visibility', 'visible')
        .before('<h3>Address found in Territory: <a href="/admin/territories/' + map_params.id + '">' + map_params.label + '</a></h3>');
}

/**
 * Retrieve a JSON listing of all MapBox maps in account.
 * (http://api.tiles.mapbox.com/v3/{username}/maps.json)
 * @returns {*}
 */
function getAllMaps() {
    return $.get('');
}

/**
 * Create a map on the fly and stick it in the DOM.
 *
 * @param e
 * @param i
 * @param label
 * @returns {boolean}
 */
function createMap(e, i, label) {
    // don't try to create the same map instance
    if ($(e).find('#map-' + i).length)
        return false;

    // create map container instance and get GitHub embed script contents (iFrame) and insert that into the target in the DOM
    var append = '<div id="map-' + i + '" class="map-container">';

    $(e).append(append);

    var map = L.mapbox.map(
        'map-' + i,
        label,
        { accessToken: 'pk.eyJ1IjoiZW5nbGVzaWRldGVycml0b3JpZXMiLCJhIjoiekFIU0NlayJ9.rE9XdicgXc9aIiXJ9yn68w' }
    );
    map.setZoom(16);
    map.scrollWheelZoom.disable();
}

function createMapboxMap(e, map_id, map_size, access_token) {
    $.getJSON('http://api.tiles.mapbox.com/v4/' + map_id + '/features.json?access_token=' + access_token, function(geojson)
    {
        var map_size = map_size || [600, 400];

        // get the outer boundaries of the given coordinates sets
        var bounds = geojsonExtent(geojson);

        // calculate the center coordinates & max zoom level to fit the features
        var viewport = geoViewport.viewport(bounds, map_size);

        L.mapbox.accessToken = access_token;

        var map = L.mapbox.map(e, map_id);
            map.setZoom(viewport.zoom);
            map.scrollWheelZoom.disable();
    });
}


// DELETE MODAL
$('body').on('click', '#delete-modal #submit', function() {
    var id       = $(this).data('id');
    var route    = $(this).data('route');
    var redirect = $(this).data('redirect');

    $.ajax({
        url:     route + '/' + id,
        type:    'DELETE',
        success: function(response) {
            $('#delete-modal').modal('hide');

            if (response.status) {
                showAlertBox(response, 'alert-success');
                setTimeout(function() {
                    window.location.href = redirect;
                }, 3000);
            } else {
                showAlertBox(response, 'alert-danger');
            }
        }
    });
});

function ajaxDelete(params) {
    $('#delete-modal').remove(); // remove any existing modals

    $.ajax({
        type:    'POST',
        url:     '/ajax/delete',
        data:    params,
        success: function(response) {
            $(response).appendTo('body');
            $('#delete-modal').modal('show');
        }
    });
}

function showAlertBox(response, alert_class) {
    $('#alert-box')
        .removeClass('hide')
        .find('.alert')
        .css({'display': 'block', 'opacity': 1})
        .removeClass('.alert-danger .alert-success')
        .addClass(alert_class)
        .find('p')
        .text(response.message);
}