L.TileLayer.BetterWMS = L.TileLayer.WMS.extend({
  
    onAdd: function (map) {
      // Triggered when the layer is added to a map.
      //   Register a click listener, then do all the upstream WMS things
      L.TileLayer.WMS.prototype.onAdd.call(this, map);
      map.on('click', this.getFeatureInfo, this);
    },
    
    onRemove: function (map) {
      // Triggered when the layer is removed from a map.
      //   Unregister a click listener, then do all the upstream WMS things
      L.TileLayer.WMS.prototype.onRemove.call(this, map);
      map.off('click', this.getFeatureInfo, this);
    },
    
    getFeatureInfo: function (evt) {
      // Make an AJAX request to the server and hope for the best
      if(featureInfoState === true){
        var url = this.getFeatureInfoUrl(evt.latlng),
          showResults = L.Util.bind(this.showGetFeatureInfo, this);
          console.log(url);
      $.ajax({
        url: url,
        success: function (data, status, xhr) {
          var err = typeof data === 'string' ? null : data;
          showResults(err, evt.latlng, data);
        },
        error: function (xhr, status, error) {
          showResults(error);  
        }
      });
      
      featureInfoState = false;
      featureInfoButton.enable();
      $('.leaflet-container').css('cursor','');
      } else {
        return;
      } 
    },
    
    getFeatureInfoUrl: function (latlng) {
      // Construct a GetFeatureInfo request URL given a point
      var point = this._map.latLngToContainerPoint(latlng, this._map.getZoom()),
          size = this._map.getSize(),
          
          params = {
            request: 'GetFeatureInfo',
            service: 'WMS',
            srs: 'EPSG:4326',
            styles: this.wmsParams.styles,
            transparent: this.wmsParams.transparent,
            version: this.wmsParams.version,      
            format: this.wmsParams.format,
            bbox: this._map.getBounds().toBBoxString(),
            height: size.y,
            width: size.x,
            //layers: this.wmsParams.layers,
            layers: 'posumljavanje:locations,posumljavanje:hrsume',
            //query_layers: this.wmsParams.layers,
            query_layers: 'posumljavanje:locations,posumljavanje:hrsume',
            info_format: 'application/json',
            feature_count: '2'
          };
      //console.log(point);
      params[params.version === '1.3.0' ? 'i' : 'x'] = Math.round(point.x);
      params[params.version === '1.3.0' ? 'j' : 'y'] = Math.round(point.y);
      
      return this._url + L.Util.getParamString(params, this._url, true);
    },
    
    showGetFeatureInfo: function (err, latlng, content) {
      if (err) { /*console.log(err);*/ /*return;*/ } // do nothing if there's an error
      
      var popupContent = '';

      if (content.features.length > 0) {
        $.each( content.features, function( key, value ) {
          if (value.id.includes('locations')) {            
            popupContent+='<h5><span style="color:#306EFF;font-size:2em;">■</span> Lokacije - '+value.properties.ime+'</h5>';
            popupContent+='<p>id: '+value.properties.id+'</p>';
            popupContent+='<p>Opis: '+value.properties.opis+'</p>';
            popupContent+='<p>Datum: '+value.properties.created_at+'</p>';
            if(adminapp) {
              popupContent+='<button onclick="deleteLocation('+value.properties.id+')" class="btn btn-danger btn-sm">Obriši</button>';
            }
            popupContent+='<br /><br />';
          } else if (value.id.includes('hrsume')) {
            popupContent+='<h5><span style="color:#ff9900;font-size:2em;">■</span> HR sume </h5>';
            popupContent+='<p>Naziv podružnice: '+value.properties.uspnaz+'</p>';
            popupContent+='<p>Šifra podružnice: '+value.properties.usp+'</p>';   
            popupContent+='<p>Šumarija: '+value.properties.sumarija+'</p>';
            popupContent+='<p>Šifra šumarije: '+value.properties.obj+'</p>';            
            popupContent+='<p>Gospodarska jedinica: '+value.properties.gj+'</p>';
            popupContent+='<p>Naziv gospodarske jedinice: '+value.properties.gjnaz+'</p>';
            popupContent+='<p>Odjel: '+value.properties.odjel+'</p>';
            popupContent+='<p>Odsjek: '+value.properties.odsjek+'</p>';
            popupContent+='<p>Naziv radova: '+value.properties.radnaz+'</p>';                     
            popupContent+='<p>Površina: '+value.properties.pov+' ha </p>';
            //popupContent+='<p>rad: '+value.properties.rad+'</p>';
            popupContent+='<p>Tehnologija: '+value.properties.teh+'</p>';            
            popupContent+='<br /><br />';        
          }          
        });
      }

      // Otherwise show the content in a popup, or something.
      L.popup({ maxWidth: 800})
        .setLatLng(latlng)
        .setContent(popupContent)
        .openOn(this._map);
    }
  });
  
  L.tileLayer.betterWms = function (url, options) {
    return new L.TileLayer.BetterWMS(url, options);  
  };