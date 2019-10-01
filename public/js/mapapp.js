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


mymap.addControl(new L.Control.Draw({   
    draw: {
        polygon: {
            allowIntersection: false,
            showArea: true
        },
        rectangle: false,
        circle: false,
        circlemarker: false,
        polyline: false,
        marker: false
    }
}));

// Clear previous drawn objects 
mymap.on(L.Draw.Event.DRAWSTART, function (event) {
    drawnItems.clearLayers();
});

mymap.on(L.Draw.Event.CREATED, function (event) {
    var layer = event.layer;

    drawnItems.addLayer(layer);

    addPopup(layer);
});

function addPopup(layer) {
    var content = document.createElement("div");

    content.innerHTML = `<div class="form-group">
                        <label>Detalji o području za pošumljavanje:</label>
                        <textarea class="form-control" id="opisArea" rows="5" style="width: 300px"></textarea>
                        </div>
                        <div id="popup-form"></div>
                        <button class="btn btn-primary" type="button" onclick="savePolygon();">Spremi</button>`;
    
    layer.bindPopup(content).openPopup();
}

function savePolygon() {
    console.log("Spremam poligon");
    var opis = document.getElementById("opisArea").value;
    
    var geojson = drawnItems.toGeoJSON();
    var geometry = geojson.features[0].geometry;
    console.log(geometry);
    
    // Append EPSG to geometry 
    geometry.crs = {"type":"name","properties":{"name":"EPSG:4326"}}; 
    
    geometry = JSON.stringify(geometry);
    
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'api/location',			
        data: {
            'opis': opis,
            'geom': geometry
        },
        success: function(msg){
            console.log(msg);

            locationsLayer.setParams({fake: Date.now()}, false);
            drawnItems.clearLayers();
        }
    });
}
 
var featureInfoButton = L.easyButton('fa fa-info-circle fa-lg', function(btn, mymap){
    featureInfoState = true;
    featureInfoButton.disable();
    $('.leaflet-container').css('cursor','help');
}).addTo( mymap );