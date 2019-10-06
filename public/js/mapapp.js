console.log('Loading adminapp...');
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
                        <label>Naziv lokacije:</label>
                        <input class="form-control" type="text" id="name">
                        <br />
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
    var name = document.getElementById("name").value;
    
    var geojson = drawnItems.toGeoJSON();
    var geometry = geojson.features[0].geometry;

    if(name == false || opis == false) {
        alert("Naziv i opis su obavezni!");
        drawnItems.clearLayers();
    } else {
        // Append EPSG to geometry
        geometry.crs = {"type":"name","properties":{"name":"EPSG:4326"}}; 
        
        geometry = JSON.stringify(geometry);
        
        $.ajax({
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'api/location',			
            data: {
                'opis': opis,
                'name': name,
                'geom': geometry
            },
            success: function(msg){
                console.log(msg);

                locationsLayer.setParams({fake: Date.now()}, false);
                drawnItems.clearLayers();
            },
            error: function(){
                alert('Pohrana nije usbjela :( \nMolimo pokušajte opet! ');
                drawnItems.clearLayers();
              }
        });
    }
}
console.log('Finished loading adminapp.');