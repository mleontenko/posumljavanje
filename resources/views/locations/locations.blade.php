@extends('layouts.app')

@section('content')
    <br />
    <h1><i class="fa fa-map-marker"></i> Lokacije za pošumljavanje</h1>
    @if(count($locations) > 0)
    <div class="row">
        @foreach($locations as $location)
        <div class="col-4 top-buffer">
            <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-tree"></i> {{$location->id}}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                <!--<p class="card-text">{{ substr($location->opis, 0, 150) }}</p>-->
                @if(strlen($location->opis) > 200)
                    <p class="card-text">{{ substr($location->opis, 0, 200) . "..." }}</p>
                @else
                    <p class="card-text">{{ substr($location->opis, 0, 200) }}</p>
                @endif                
                <a href="#" class="card-link">Prikaži na karti</a>
                <a href="#" class="card-link">Briši</a>
            </div>
            </div>
        </div>
        @endforeach
    </div>
    <br />       
    {{$locations->links()}}
    @else
        <div class="card">
            <div class="card-body">
                <p>Nema lokacija.</p>
            </div>
        </div>      
    @endif
@endsection