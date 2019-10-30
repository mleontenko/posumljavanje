console.log('Loading adminapp...');

adminapp = true;

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
                        <br />
                        <label>Broj sadnica:</label>
                        <input class="form-control" type="number" id="seedlings" value=0>
                        <br />
                        <label>Slika (maksimalna veličina 5MB):</label>                                                    
                        <input id="sortpicture" type="file" name="sortpic" />         
                        </div>
                        <div id="popup-form"></div>
                        <button class="btn btn-primary" type="button" onclick="savePolygon();">Spremi</button>`;
    
    layer.bindPopup(content).openPopup();
}

function savePolygon() {
    console.log("Spremam poligon");
    var opis = document.getElementById("opisArea").value;
    var name = document.getElementById("name").value;
    var seedlings = document.getElementById("seedlings").value;
    
    var file_data = $('#sortpicture').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    form_data.append('opis', opis);
    form_data.append('name', name);
    form_data.append('seedlings', seedlings);
    
    var geojson = drawnItems.toGeoJSON();
    var geometry = geojson.features[0].geometry;
    
    if(name == false || opis == false) {
        alert("Naziv i opis su obavezni!");
        drawnItems.clearLayers();
    } else {
        // Append EPSG to geometry
        geometry.crs = {"type":"name","properties":{"name":"EPSG:4326"}}; 
        
        geometry = JSON.stringify(geometry);
        form_data.append('geom', geometry);
        
        $.ajax({
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'api/location',			
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(msg){
                console.log(msg);

                locationsLayer.setParams({fake: Date.now()}, false);
                drawnItems.clearLayers();
            },
            error: function(){
                alert('Pohrana nije uspjela :( \nMolimo pokušajte opet! ');
                drawnItems.clearLayers();
              }
        });
    }
}

function deleteLocation(id) {
    $.ajax({
        url: 'api/location/'+id,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result) {
            locationsLayer.setParams({fake: Date.now()}, false);
            mymap.closePopup();
        },
        error: function(){
            alert('Brisanje nije uspjelo :( \nNapomena: Možete brisati samo lokacije koje ste sami ucrtali! ');
            drawnItems.clearLayers();
        }
    });
}

function editLocation(id) {
    $("#leaflet-popup-div").empty();

    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'api/location/'+id,			
        
        success: function(msg){
            console.log(msg);

            var content = document.createElement("div");

            content.innerHTML = `<div class="form-group">
                                <label>* Naziv lokacije:</label>
                                <input class="form-control" type="text" id="name" value="`+msg.name+`">
                                <br />
                                <label>Detalji o području za pošumljavanje:</label>
                                <textarea class="form-control" id="opisArea" rows="5" >`+msg.opis+`</textarea>
                                <br />
                                <label>Broj sadnica:</label>
                                <input class="form-control" type="number" id="seedlings" value=`+msg.seedlings+`>
                                <br />
                                </div>
                                <div id="popup-form"></div>
                                <button class="btn btn-primary" type="button" onclick="saveEditedPolygon(`+id+`);">Spremi</button>`;

            document.getElementById("leaflet-popup-div").appendChild(content);
        }
    });
}

function saveEditedPolygon(id) {
    var opis = document.getElementById("opisArea").value;
    var name = document.getElementById("name").value;
    var seedlings = document.getElementById("seedlings").value;

    $.ajax({
        url: 'api/location/'+id,
        type: 'PUT',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {name: name, opis: opis, seedlings: seedlings},
        success: function(result) {
            mymap.closePopup();
        },
        error: function(){
            alert('Uređivanje nije uspjelo :( \nNapomena: Možete uređivati samo lokacije koje ste sami ucrtali! \nNaziv lokacije i detalji o području su obavezni!');
            drawnItems.clearLayers();
        }
    });
}

console.log('Finished loading adminapp.');