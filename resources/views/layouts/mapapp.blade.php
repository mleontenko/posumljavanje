
<!DOCTYPE html>
<html>
<head>
	
	<title>Pošumljavanje</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <!--<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>-->
    <!--<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<style>
        .navbar { margin-bottom: 0; }

        #mapid {
            height: calc(100vh - 55px); /* 100% of the viewport height - navbar height */
        }
    </style>
</head>
<body>


<div id="app">
    @include('inc.navbar')
    <div id="mapid"></div>
</div>

<!-- Modal -->
<!--
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Kartiranje područja</h4>
            </div>
            <div class="modal-body">
              <p>Autorizirani korisnici imaju pristup kontrolama za ucrtavanje novih područja na kartu.</p>

              Područje se crta klikom na tipku<img src="icons/polygon.png" alt="">u gornjem lijevom uglu.
          </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Shvaćam</button>
		</div>          
    </div>
</div>
-->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kartiranje područja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  	<p>Autorizirani korisnici imaju pristup kontrolama za ucrtavanje novih područja na kartu.</p>

		<p>Područje se crta klikom na tipku<img src="icons/polygon.png" alt="">u gornjem lijevom uglu.</p>

		<p>Informacije o području dobiju se klikom na tipku<img src="icons/info.png" alt="">u gornjem lijevom uglu. Nakon toga je potrebno kliknuti na lokaciju na karti da bi se dobio popup prozor sa atributima.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Shvaćam</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/app.js')}}"></script>
<script>
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
</script>



</body>
</html>
