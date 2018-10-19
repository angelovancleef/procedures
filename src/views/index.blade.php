@extends('welcome')
@section('content')

    <!-- Stuff needed to style our menu -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <!-- End of needed stuff -->

    <!-- Some styling that we need specific on this page -->
    <style>
    pre {
        float:left;
        width:100%;
        border:none;
    }

    pre.prettyprint {
        border:0px;
        background:#F8F8F8;
        margin:0px;
        padding:0px;
        padding-left:12px;
    }

    .invisible {
        display:none;
    }

    .parameter {
        display:block;
    }

    b {
        font-weight:600;
    }
    </style>
    <!-- End of needed styling -->

    <div class="container" style="margin-top:50px;">
        <div class="z-depth-1" style="padding:12px;">
            <div class="row">
                <div class="col s12">

                    <form name="procedure_export_form" id="procedure_export_form" method="post">
                        <table class="striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Database</th>
                                <th>Created</th>
                                <th>Altered</th>
                                <th>Definition</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>

                                @foreach($list as $list)

                                    <tr>
                                        <td>{{ $list->SPECIFIC_NAME }}</td>
                                        <td>{{ $list->ROUTINE_SCHEMA }}</td>
                                        <td>{{ $list->CREATED }}</td>
                                        <td>{{ $list->LAST_ALTERED }}</td>
                                        <td>
                                            <a href="#" title="Click to see definition" onclick="seeDefinition(event); return false;">
                                                Click to see definition
                                            </a>
                                        </td>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="export[]" value="{{ $list->SPECIFIC_NAME  }}|{{ $list->ROUTINE_SCHEMA }}" />
                                                <span>&nbsp;</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="invisible">
                                        <td colspan="6">
                                            <pre class="prettyprint">{{ $list->ROUTINE_DEFINITION }}</pre>
                                        </td>
                                    </tr>
                                    <tr style="margin-bottom:85px;">
                                        <td colspan="6">

                                            @for($i = 0; $i < count($list->addedInformationData); $i++)

                                                <div class="parameter"><b>Parameter:</b> {{ $list->addedInformationData[$i]->SPECIFIC_NAME }}</div>
                                                <div class="parameter"><b>Type:</b> {{ $list->addedInformationData[$i]->DATA_TYPE }}</div>
                                                <div class="parameter"><b>Position:</b> {{ $list->addedInformationData[$i]->ORDINAL_POSITION }}</div>

                                                @if($i < count($list->addedInformationData) - 1)
                                                    <hr />
                                                @endif

                                            @endfor

                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>
                        </table>

                        <a class="waves-effect waves-light btn" onclick="document.getElementById('procedure_export_form').submit()"><i class="material-icons left">cloud</i>export</a>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Some manditory Javascript -->
    <script>
    function seeDefinition(event)
    {
        event.target.offsetParent.parentElement.nextElementSibling.classList.toggle('invisible')
    }
    </script>
    <!-- End of needed Javascript -->

@endsection