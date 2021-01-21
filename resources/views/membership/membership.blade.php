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
            <h3>Administracija</h3>
            @include('inc.messages')     
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Ime i prezime</th>
                        <th scope="col">email</th>
                        <th scope="col">Županija</th>
                        <th scope="col">Verificiran</th>                        
                        <th scope="col">Verificiraj</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                <tr>
                    <td>{{$member->id}}</td>
                    <td>{{$member->name}}</td>
                    <td>{{$member->email}}</td>
                    <td>{{$member->county}}</td> 
                    <td>
                        @if($member->verified == FALSE)
                            NE                
                        @else
                            DA
                        @endif
                    </td>                     
                    <td>
                        @if($member->verified == FALSE)
                            <form action="{{ route('membership.update', $member->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="submit" class="btn btn-primary" value="Verificiraj">
                            </form>
                        @else
                            <button type="button" class="btn btn-primary" disabled>Verificiraj</button>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>    
        </div>
    </div>

<!-- Compiled JS from libraries -->
<script src="{{ asset('js/app.js')}}"></script>

</body>
</html>