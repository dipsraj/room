@extends('layouts.master')
@php($title = config('app.name'))
@section('page-title','Show Floor - '.$title)
@section('custom-include')
    <link rel="stylesheet" href="{{asset('css/custom_css/alertmessage.css')}}">
@endsection
<style>
    #watertank{
        background: url({{ URL::asset('images/tank.png') }}) no-repeat;
        height: 229px;
        padding-top: 34px;
        position: relative;
    }

    .fill {
        width: 168px;
        height: 0px;
        border: 1px solid #000;
        background: green;
        left: 4px;
        border-radius: 11px;
        position: absolute;
        bottom: 37px;
    }
    .percentage {
        margin-left: 66px;
        color: #fff;
    }
    .red {
        background-color: #FF0000;
    }
    .green {
        background-color: #33CC00;
    }
</style>

@section('content')
    <div class="container">
        <input type="button" class="btn btn-primary" value="Refresh" onclick="refresh();">
        <input type="button" id="master_controll" class="btn @if($data['master_control'] == 0) btn-primary @else btn-warning @endif" value="@if($data['master_control'] == 0) ON @else OFF @endif" onclick="master_control();">
        <input type="button" id="change_pump" class="btn btn-primary" value="Change Pump" onclick="change_pump();">
        <div>
            <p>Reserver Water Lavel: <span id="reserver-status">...</span></p>
            <p>Pump: @if($data['pump_running_status'] == 0) <span class="red">OFF</span> @else <span class="green">ON</span> @endif</p>
            <p>Selected Pump is: Pump{{ $data['last_selected_pump'] }}</p>

        </div>
        <div id="watertank">
            <div class="fill">
                <span class="percentage"></span>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        function fetch_water_level() {
            var url = "http://"+"{{ $data['ip'] }}" + "/waterLavel";
            var high_level = "{{ $data['high_level'] }}";
            $.ajax({
                url: url, success: function (result) {
                    result = result.replace("'", '"');
                    result = result.replace("'", '"');
                    result = JSON.parse(result);
                    var reverse_distance = (result.water_level - high_level);
                    if(reverse_distance < 0) {
                        reverse_distance = 0;
                    }
                    var reverse_percentage = (31 * reverse_distance) / 100;
                    reverse_percentage = (100 - reverse_percentage);
                    /*var result_percentage = 0;
                    result_percentage = (100 * result.water_level) / 100;
                    result_percentage = 100 - result_percentage;*/
                    var css_full = 152;
                    var css_water_lavel = (css_full * reverse_percentage) / 100;
                    if(css_water_lavel < 0) {
                        css_water_lavel = 0;
                    }
                    $(".fill").height(css_water_lavel)
                    $(".fill span").html(reverse_percentage+"% (" + result.water_level + ")");
                    console.log(result);
                }
            });
        }

/*        function fetch_pump_status() {
            var url = "http://"+"{{ $data['ip'] }}" + "/status?type=PUMP";
            $.ajax({
                url: url, success: function (result) {
                    result = result.replace("'", '"');
                    result = result.replace("'", '"');
                    result = JSON.parse(result);

                    if(result.pump_on_status == 1) {
                        $("#pump-on-status").html("ON");
                    }
                    else {
                        $("#pump-on-status").html("OFF");
                    }
                    console.log(result);
                }
            });
        }*/
        function fetch_reserver_status() {
            var url = "http://"+"{{ $data['ip'] }}" + "/status?type=RESERVER";
            $.ajax({
                url: url, success: function (result) {
                    result = result.replace("'", '"');
                    result = result.replace("'", '"');
                    result = JSON.parse(result);
                    if(result.reserver_status == 0) {
                        $("#reserver-status").html("OK (DEBUG " + result.reserver_status + ")");
                    }
                    else {
                        $("#reserver-status").html("LOW (DEBUG " + result.reserver_status + ")");
                    }
                }
            });
        }
        //setInterval(fetch_water_level,1000);
        //setInterval(fetch_pump_status,1000);
        //setInterval(fetch_reserver_status,1000);
        fetch_water_level();
        //fetch_pump_status();
        fetch_reserver_status();

        function refresh() {
            fetch_water_level();
            fetch_pump_status();
            fetch_reserver_status();
        }
        
        function master_control() {
            /*$.post( "pump/master-control", function( data ) {

            });*/
            $.ajax({
                url : "pump/master-control",
                type: "POST",
                data : null,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data, textStatus, jqXHR)
                {
                    var a =0;
                    data = JSON.parse(data);
                    if(data.master_control == 1) {
                        $("#master_controll").attr('value', 'OFF');
                    }
                    else {
                        $("#master_controll").attr('value', 'ON');
                    }
                    //data - response from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                }
            });
        }
        
        function change_pump() {
            $.ajax({
                url : "pump/change-pump",
                type: "POST",
                data : null,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data, textStatus, jqXHR)
                {
                    console.log(data);
                    var a =0;
                    data = JSON.parse(data);
                    if(data.success == 1) {
                        alert("Pump is changed");
                        window.location.reload();
                    }
                    else {
                        alert(data.err_msg);
                    }
                    //data - response from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                }
            });
        }
    </script>
@endsection

