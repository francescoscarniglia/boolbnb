@extends('layouts.app')

@section('content')
<div class="place-show">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="place-show__top">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12 order-2 order-lg-1">
                    <h3 class="place-show__top__title text-center">{{$place->title}}</h3>
                    <h5 class="place-show__top__position text-center">{{$place->address}} - {{$place->city}}</h5>
                    <p class="place-show__top__description">{{$place->description}}</p>
                    <hr>
                    <h6>Caratteristiche:</h6>
                    <ul class="place-show__top__list">
                        <li class="place-show__top__list__info">Numero stanze: {{$place->num_rooms}}</li>
                        <li class="place-show__top__list__info">Posti letto: {{$place->num_beds}}</li>
                        <li class="place-show__top__list__info">Bagni: {{$place->num_baths}}</li>
                        <li class="place-show__top__list__info">Dimensioni: {{$place->square_m}}m²</li>
                    </ul>
                    <hr>
                    {{-- Servizi --}}
                    <div class="place-show__top__amenities d-flex align-items-center">
                        <span class="d-inline-block mr-2">Servizi inclusi</span>
                        @forelse ( $place->amenities as $amenity )
                            <span class="badge badge-pill mr-1">{{ $amenity->name }}</span>
                        @empty
                            <span class="badge badge-pill badge-info">Nessun servizio incluso</span>
                        @endforelse
                    </div>
                    <h5 class="place-show__top__price text-lg-right text-md-right text-center my-3 h3 font-weight-bold">{{round($place->price)}}€ a notte</h5>
                    <input type="hidden" name="lat" id="lat" value="{{ $place->lat }}">
                    <input type="hidden" name="long" id="long" value="{{ $place->long }}">
                </div>
    
                <div class="col-lg-6 col-md-12 col-sm-12 order-1 order-lg-2 mb-3 mb-lg-0">
                    @if(!empty($place->place_img))
                        <img src="{{asset('storage/' . $place->place_img)}}" class="img-fluid rounded" alt="immaginecasa" style="max-width: 100%; height: auto;">
                    @else
                        <div class="no-image text-danger">No image</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="place-show__bottom">
        @auth
            @if ($place->user_id === $user->id)
                <div id="mapid" class="rounded-lg" style="height: 300px"></div>
            @else
                <div class="container">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div id="mapid" class="rounded-lg" style="height: 300px"></div>
                        </div> 
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <h5 class="text-center">Contatta il venditore</h5>
                            @include('shared.sendMessageArea')
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        @guest
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div id="mapid" class="rounded-lg" style="height: 300px"></div>
                    </div> 
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <h5 class="text-center">Contatta il venditore</h5>
                        @include('shared.sendMessageArea')
                    </div>
                </div>
            </div>
        @endguest
    </div>

    @auth
        @if ($place->user_id === $user->id)
            <div class="place-show__stats">
                <div class="container">
                    <h2 class="place-show__stats__title text-center">Area Statistiche <i class="fas fa-chart-line"></i></h2><hr>
                    <div class="row d-flex align-items-center">
                        <div class="place-show__stats__totals col-lg-4 col-md-4 col-sm-12">
                            <h3 class="place-show__stats__totals__msg text-lg-right text-md-right text-sm-center">Totale contatti:<br><span class="h2 d-flex align-items-center justify-content-end" style="font-size: 50px; font-weight: 700;"><i class="far fa-envelope" style="margin-right: 25px; font-size: 30px"></i>{{$totMessages}}</span></h3>
                            <hr>
                            <h3 class="place-show__stats__totals__visits text-lg-right text-md-right text-sm-center">Totale visite:<br><span class="h2 d-flex align-items-center justify-content-end" style="font-size: 50px; font-weight: 700;"><i class="far fa-eye" style="margin-right: 25px; font-size: 30px"></i>{{$totVisits}}</span></h3>
                        </div>
                        <div class="place-show__stats__graph col-lg-8 col-md-8 col-sm-12">
                            <canvas id="graph" class=""></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>



<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>

{{-- Json Statistiche Messaggi --}}
<script>var messagesGraph = @php echo json_encode($messagesGraph);@endphp</script>
<script>var visitsGraph = @php echo json_encode($visitsGraph);@endphp</script>
{{-- JS --}}
<script src="{{asset('js/graphs.js')}}"></script>
<script src="{{ asset('js/map.js') }}"></script>
@endsection