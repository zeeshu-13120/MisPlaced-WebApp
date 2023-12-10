@extends('Layout.layout')
@section('title', 'Post Details')
@section('style')
    <style>
        .checkout-wrapper {
            background-color: #f8f9fa; /* Set a light background color */
        }

        .post-details {
            background-color: #fff; /* Set a white background color */
            border-radius: 0.5rem; /* Add some border-radius for rounded corners */
            padding: 20px;
            margin-bottom: 20px;
        }

        .post-details ul {
            list-style: none; /* Remove list styling */
            padding: 0;
        }

        .post-details li {
            padding: 10px 0;
        }


    </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@endsection

@section('content')
{{-- {{dd($post)}} --}}
        <div class="container py-4">
            <section class="row mx-auto post-details  rounded-3 align-self-stretch">
                <h1 class="mb-4 text-center">Post Details</h1>

                <div class="col-md-6"> <!-- Use two columns on medium and larger screens -->
                    <ul>
                        <li><strong>User Name:</strong> {{ $post->first_name }} {{ $post->last_name }}</li>
                        <li><strong>Email:</strong> {{ $post->email }}</li>
                        <li><strong>Post Created Date Time:</strong> {{ $post->created_at }}</li>
                        <li><strong>Status:</strong> {{ $post->status }}</li>
                        <li><strong>Post Type:</strong> {{ $post->post_type }}</li>
                        <li><strong>Category:</strong> {{ $post->category_name }}</li>
                    </ul>
                </div>

                <div class="col-md-6"> <!-- Use two columns on medium and larger screens -->
                    <ul>
                        <!-- Parse and display formdata values -->
                        @if(isset($post->formdata))
                            @foreach(json_decode($post->formdata) as $formField)
                                @if(isset($post->{$formField->name}))
                                    <li>
                                        @if($formField->type === 'file')
                                            <strong>{{ $formField->label }}:</strong>
                                            <button class="btn btn-primary"
                                                    onclick="window.location.href='{{ asset($post->{$formField->name}) }}'">
                                                Download
                                            </button>
                                        @else
                                            <strong>{{ $formField->label }}:</strong> {{ $post->{$formField->name} }}
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div id="map" class="col-12" style="height: 430px;"></div>

            </section>
        </div>
@endsection

@section('script')
<script>
    // Parse the user_route_cords string into an array of coordinate pairs
    var routeCords = "{{ $post->user_route_cords }}".split('|').map(function(pair) {
        return pair.split(',').map(function(coord) {
            return parseFloat(coord);
        });
    });

    // Initialize the map
    var map = L.map('map').setView(routeCords[0], 13);

    // Add a tile layer to the map (you can replace this with your preferred tile layer)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers to the map
    for (var i = 0; i < routeCords.length; i++) {
        L.marker(routeCords[i]).addTo(map);
    }

    // Add a polyline to connect all markers
    L.polyline(routeCords, { color: 'blue' }).addTo(map);
</script>


@endsection
