console.log('Loading guestapp...');
$(window).on('load',function(){
    if(!sharedZoom) {
        $('#exampleModal').modal('show');
    }    
});

var featureInfoState = false;

var mymap = L.map('mapid', { minZoom: 7, maxZoom: 18, center: new L.LatLng(44.650, 16.708), zoom: 7, maxBounds: [[40.995, 11.572], [48.256, 22.920]] });

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="https://www.crogis.hr/">CROGIS</a>, powered by <a href="https://www.li-st.net/">LI:ST</a>'
}).addTo(mymap);

var locationsLayer = L.tileLayer.betterWms('https://dev.li-st.net/geoserver/posumljavanje/wms', {
    layers: 'locations',
    transparent: true,
    format: 'image/png'
  }).addTo(mymap);

var hrsume = L.tileLayer.betterWms('https://dev.li-st.net/geoserver/posumljavanje/wms', {
    layers: 'hrsume',
    transparent: true,
    format: 'image/png'
}).addTo(mymap);

drawnItems = L.featureGroup().addTo(mymap);
L.control.scale({ imperial: false }).addTo(mymap);
 
var featureInfoButton = L.easyButton('fa fa-info-circle fa-lg', function(btn, mymap){
    featureInfoState = true;
    featureInfoButton.disable();
    $('.leaflet-container').css('cursor','help');
}, 'Info o lokaciji').addTo( mymap );

var shareButton = L.easyButton('fa fa-share-alt fa-lg', function(){
    var center = mymap.getCenter();
    var lat = center.lat;
    var lng = center.lng;
    var zoom= mymap.getZoom();
    var shareUrl = 'https://panj.crogis.hr?lat='+lat+'&lng='+lng+'&zoom='+zoom;
    //console.log(shareUrl);
    $('#shareModal').modal('show');
    $("#link-field").html('<code>'+shareUrl+'</code>');
}, 'Podijeli').addTo( mymap );

// Print
var printer = L.easyPrint({
    // tileLayer: tiles,
    sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
    filename: 'karta',
    exportOnly: true,
    hideControlContainer: true
}).addTo(mymap);

var adminapp = false;

/*
 * Method for reading parameters from query string (used for displaying shared views)
 */
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

/*
 * Read parameters from query string using getParameterByName() method 
 */
var sharedLat = getParameterByName('lat');
var sharedLng = getParameterByName('lng');
var sharedZoom = getParameterByName('zoom');

/*
 * Set map to specified view if shared
 */
if(sharedLat && sharedLng && sharedZoom) {
    mymap.setView([sharedLat, sharedLng], sharedZoom);
}

console.log('Finished loading guestapp...');