<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('inc.navbar')
        <div class="container">     
    <br />
    <h1><i class="fa fa-map-marker"></i> Lokacije za pošumljavanje</h1>
    <p>Ukupan broj sadnica: {{ $locations->sum('seedlings') }}</p>
    @if(count($locations) > 0)
    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naziv</th>
                <th>Opis</th>
                <th>Koordinator</th>
                <th>Broj sadnica</th>
                <th>Poveznica</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locations as $location)
            <tr>
                <td>{{$location->id}}</td>
                <td>{{$location->ime}}</td>
                @if(strlen($location->opis) > 100)
                    <td>{{ substr($location->opis, 0, 100) . "..." }}</td>
                @else
                    <td>{{ substr($location->opis, 0, 100) }}</td>
                @endif  
                <td>{{$location->county}}</td>
                <td>{{$location->seedlings}}</td>
                <td><a href="https://panj.crogis.hr?lat={{ $location->st_y }}&lng={{ $location->st_x }}&zoom=17">Karta</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="card">
            <div class="card-body">
                <p>Nema lokacija.</p>
            </div>
        </div>      
    @endif
    </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );
    </script>    
</body>
</html>