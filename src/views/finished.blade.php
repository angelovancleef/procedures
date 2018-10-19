@extends('welcome')
@section('content')

    <!-- Stuff needed to style our menu -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- End of needed stuff -->

    <div class="container" style="margin-top:50px;">
        <div class="z-depth-1" style="padding:12px;">
            <div class="row">
                <div class="col s12">

                    @if(count($failed) > 0)

                        One or more procedure migrations already exists. Please delete these before migrating them again. The migration(s) are: <br />

                        @foreach($failed as $fail)

                            {{ $fail }} <br />

                        @endforeach

                    @else

                        Succesfully migrated all selected procedures without any errors.

                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection