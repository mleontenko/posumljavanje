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
        @auth
        <p>Autorizirani korisnici imaju pristup kontrolama za ucrtavanje novih područja na kartu.</p>
        
        <p>Područje se crta klikom na tipku<img src="icons/polygon.png" alt="">u gornjem lijevom uglu.</p>
        @endauth
        @guest
        <p>Dobrodošli! Ovdje možete pregledavati područja za pošumljavanje. </p>        
        @endguest

        <p>Informacije o području dobiju se klikom na tipku<img src="icons/info.png" alt="">u gornjem lijevom uglu. Nakon toga je potrebno kliknuti na lokaciju na karti da bi se dobio popup prozor sa atributima.</p>

        <p>Kartu je moguće isprintati klikom na tipku<img src="icons/print.png" alt="">u gornjem lijevom uglu. Moguće je printati trenutni prozor ili A4 format u panoramskoj ili portret orijentaciji.</p>

        <p>Kartu je moguće podijeliti klikom na tipku<img src="icons/share.png" alt="">u gornjem lijevom uglu. Potrebno je samo kopirati i poslati link generiran u popup prozoru.</p>

        <p style="margin-left:5px"><span style="color:#306EFF;font-size:2em;">■</span> Lokacije za pošumljavanje</p>
        <p style="margin-left:5px"><span style="color:#ff9900;font-size:2em;">■</span> HR Šume - Površine predviđene za sjetvu i sadnju</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Shvaćam</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for sharing link-->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Podijeli lokaciju</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Poveznica za dijeljenje (kopirajte link ispod):</p>
        <div id="link-field"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Zatvori</button>
      </div>
    </div>
  </div>
</div>

<!-- Compiled JS from libraries -->
<script src="{{ asset('js/app.js')}}"></script>

<!-- Custom JS  -->
<script src="{{ asset('js/guestapp.js')}}"></script>
@auth
  <script src="{{ asset('js/mapapp.js')}}"></script>
@endauth
</body>
</html>
