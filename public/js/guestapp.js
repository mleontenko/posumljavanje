console.log('Loading guestapp...');
$(window).on('load',function(){
    $('#exampleModal').modal('show');
});

var featureInfoState = false;

var mymap = L.map('mapid').setView([44.71, 16.46], 7);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
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
}).addTo( mymap );


// Print
var printer = L.easyPrint({
    // tileLayer: tiles,
    sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
    filename: 'karta',
    exportOnly: true,
    hideControlContainer: true
}).addTo(mymap);
console.log('Finished loading guestapp...');