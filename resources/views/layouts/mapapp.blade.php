
<!DOCTYPE html>
<html>
<head>	
	<title>Pošumljavanje</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Compiled CSS from libraries -->  
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!-- Custom CSS (public/css/custom.css)-->
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">	
</head>
<body>


<div id="app">
    @include('inc.navbar')
    <div id="mapid"></div>
</div>

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

<!-- Compiled JS from libraries -->
<script src="{{ asset('js/app.js')}}"></script>

<!-- Custom JS  -->
<script src="{{ asset('js/mapapp.js')}}"></script>
</body>
</html>
