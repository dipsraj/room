@extends('layouts.master')
@php($title = config('app.name'))
@section('page-title','Room - '.$title)
@section('custom-include')
    <link rel="stylesheet" href="{{asset('css/custom_css/alertmessage.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/Hover/css/hover.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/Buttons/css/buttons.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom_css/advbuttons.css')}}">
@endsection
@section('custom-internal-css')
    <style>
        .flex-grid {
            display: flex;
            flex-wrap: wrap;
            text-align: center;
        }

        .col {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            margin: 10px;
            min-width: 80px;
            min-height: 80px;
            background-color: #d3be2c;
            cursor: pointer;
        }

        .col > h3 {
            margin: 0;
        }
        .hide {
            display: none;
        }
        .show {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-success hide" id="alert-success">
                    <strong>Success!</strong> Request Process Successfully Done.
                </div>
                <div class="alert alert-danger hide" id="alert-error">
                    Something Wrong
                </div>
                <div class="panel panel-info">

                    <div class="panel-heading">
                        <h3 class="panel-title">Room - {{ $room->name }}</h3>
                    </div>

                    <div class="panel-body" style="text-align: center">

                        <div class="panel ">
                            <div class="panel-heading">
                                <h4 class="panel-title">List of Items</h4>
                            </div>
                            <div class="panel-body">
                                <div class="flatbuttons">
                                    <ul>
                                    @foreach($items as $item)
                                        <li>
                                            <a href="#" onclick="task('{{ $item->item_code }}')" class="button button-rounded button-flat-primary hang">{{ $item->name }}</a>
                                        </li>

                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        function task(id) {
            var url = "{{ URL::to('/task') }}" + "/" + id;
            $.ajax({
                url: url, success: function (result) {
                    if(result == 1) {
                        $("#alert-success").addClass('show');
                        $("#alert-success").removeClass('hide');

                        $("#alert-error").addClass('hide');
                        $("#alert-error").removeClass('show');
                    }
                    else {
                        $("#alert-success").addClass('hide');
                        $("#alert-success").removeClass('show');

                        $("#alert-error").addClass('show');
                        $("#alert-error").removeClass('hide');
                    }
                    console.log(result);
                }
            });
        }
    </script>
@endsection