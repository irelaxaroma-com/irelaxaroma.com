@extends('layouts.master')
@section('content')
    <!-- wishlist Content -->
    <section class="wishlist-content pro-content">

        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="heading">
                        <h2>
                            My Account
                        </h2>
                        <hr>
                    </div>

                    @include('includes.side-menu')

                </div>
                <div class="col-12 col-lg-9">
                    <div class="heading" style="display:flex; justify-content:space-between">
                        <div style="padding: 16px 0">
                            <h2>
                                Points
                            </h2>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-secondary swipe-to-top redeem">
                                Redeem Now
                            </button>
                        </div>
                        <div style="padding: 16px 0">
                            <h2>Total Points:  <span style="color: rgb(207, 51, 51)">{{ $totalPoint }}</span>
                            </h2>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12 px-0 media-main" id="points-template">

                    </div>

                    <div class="heading mt-2" style="display:flex; justify-content:space-between">
                        <div style="padding: 10px 0">
                            <p class="font-weight-bold">
                                <span style="font-size: 25px; color: rgb(207, 51, 51)">*</span>
                                You can redeem your points when it 100
                            </p>
                        </div>
                        <div class="mt-2" style="padding: 10px 0">
                            <p class="font-weight-bold">
                                <span style="font-size: 25px; color: rgb(207, 51, 51)">*</span> Per Point Equal: <span style="color: rgb(207, 51, 51)">{{ $setting['per_point'] }} {{ $data['selectedCurrencySymbol'] }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- ............the end..... -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        loggedIn = $.trim(localStorage.getItem("customerLoggedin"));
        if (loggedIn != '1') {
            window.location.href = "{{ url('/') }}";
        }

        languageId = localStorage.getItem("languageId");
        if (languageId == null || languageId == 'null') {
            localStorage.setItem("languageId", '1');
            $(".language-default-name").html('Endlish');
            localStorage.setItem("languageName", 'English');
            languageId = 1;
        }

        cartSession = $.trim(localStorage.getItem("cartSession"));
        if (cartSession == null || cartSession == 'null') {
            cartSession = '';
        }
        loggedIn = $.trim(localStorage.getItem("customerLoggedin"));
        customerToken = $.trim(localStorage.getItem("customerToken"));
        customerId = $.trim(localStorage.getItem("customerId"));


        fetchPoints();

        function fetchPoints() {
            var url = "{{ env('url') }}" +
                '/api/client/points';
            $.ajax({
                type: 'get',
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + customerToken,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    console.log(data);
                    if (data.status == 'Success') {
                        var html = "<div class='container row'>";
                        html += "<div class='col-md-4' style='padding:6px !important'><b>Description</b></div>";
                        html += "<div class='col-md-4' style='padding:6px !important'><b>Points</b></div>";
                        html += "<div class='col-md-4' style='padding:6px !important'><b>Redeem</b></div>";

                        for (i = 0; i < data.data.length; i++) {
                            if (data.data[i].status == '0') {
                                html += "<div class='col-md-4' style='padding:6px !important'>" + data.data[i].description + "</div>";
                                html += "<div class='col-md-4' style='padding:6px !important'>" + data.data[i].points + "</div>";
                                html += "<div class='col-md-4' style='padding:6px !important'>" + 'No' + "</div>";
                            } else {
                                html += "<div class='col-md-4' style='padding:6px !important'>" + data.data[i].description + "</div>";
                                html += "<div class='col-md-4' style='padding:6px !important'>" + data.data[i].points + "</div>";
                                html += "<div class='col-md-4' style='padding:6px !important'>" + 'Yes' + "</div>";
                            }



                        }
                        html += "</div>";
                        $('#points-template').html(html);

                    }
                },
                error: function(data) {},
            });
        }

        $('.redeem').click(function (e) { 
            e.preventDefault();
            var url = "{{ env('url') }}" +
                '/api/client/redeem';
            $.ajax({
                type: 'post',
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + customerToken,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    console.log();
                    if (data.status == 'Success') {
                        toastr.success('{{ trans('points-redeem-successfully') }}');
                    } else if (data.status == 'Error') {
                        toastr.error('{{ trans('response.some_thing_went_wrong') }}');
                    }
                },
                error: function(data) {
                    console.log();
                    if (data.status == 422) {
                        jQuery.each(data.responseJSON.errors, function(index, item) {
                            $("#" + index).parent().find('.invalid-feedback').css('display',
                                'block');
                            $("#" + index).parent().find('.invalid-feedback').html(item);
                        });
                    } else if (data.status == 401) {
                        console.log(data);
                        toastr.error(data.responseJSON.message);
                    } else {
                        toastr.error('{{ trans('response.some_thing_went_wrong') }}');
                    }
                },
            });
        });
    </script>
@endsection
