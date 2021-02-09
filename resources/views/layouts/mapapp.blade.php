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
        <div class="row mymodal-align">
          <div class="col-xs-6">
            <a href="https://www.crogis.hr/" target="_blank">
              <img src="icons\cropped-logo-za-web8.png" class="rounded mx-auto d-block" alt="..." width="90" height="80">
              <h5 class="text-center">CROGIS</h5>
            </a>
          </div>
          <div class="col-xs-6">
            <a href="https://zasadistablonebudipanj.hr/" target="_blank">
              <img src="icons\udruga_logo.jpg" class="rounded mx-auto d-block" alt="..." width="90" height="80">          
            </a>
          </div>
        </div>
        
      
        @auth
        <p>Autorizirani korisnici imaju pristup kontrolama za ucrtavanje novih područja na kartu.</p>
        
        <p>Područje se crta klikom na tipku<img src="icons/polygon.png" alt="">u gornjem lijevom uglu.</p>
        @endauth
        @guest
        <p>Dobrodošli na GIS portal za pošumljavanje udruge UZSNP</p>
        <p>Svjesni potrebe za djelovanjem u utrci s klimatskom krizom, nudimo odgovor na istu u
            obliku sadnje stabala, kako bi svi zajedno podizali ekološku svijest
            i potvrdili društvenu i ekološku odgovornost te na taj način potpomogli u
            realizaciji strategija ublažavanja posljedica klimatskih promjena prema EU
            standardima, poboljšali kvalitetu naših životnih i radnih sredina, 

            te ublažili postojeće posljedice klimatske krize 
        </p>        
        @endguest

        <p>Informacije o području dobiju se klikom na tipku<img src="icons/info.png" alt="">u gornjem lijevom uglu. Nakon toga je potrebno kliknuti na lokaciju na karti da bi se dobio popup prozor sa atributima.</p>

        <p>Kartu je moguće isprintati klikom na tipku<img src="icons/print.png" alt="">u gornjem lijevom uglu. Moguće je printati trenutni prozor ili A4 format u panoramskoj ili portret orijentaciji.</p>

        <p>Kartu je moguće podijeliti klikom na tipku<img src="icons/share.png" alt="">u gornjem lijevom uglu. Potrebno je samo kopirati i poslati link generiran u popup prozoru.</p>

        <p style="margin-left:5px"><span style="color:#0000ff;font-size:1.6em;">▨</span> Lokacije za pošumljavanje</p>
        <p style="margin-left:5px"><span style="color:#ff9900;font-size:2em;">■</span> HR Šume - Površine predviđene za sjetvu i sadnju</p>
        <a href="https://www.li-st.net/" class="float-right" target="_blank">
          <p>Powered by:</p>
          <img src="icons\list_logo_small.png" class="rounded mx-auto d-block" alt="..." width="60" height="22">          
        </a>
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
