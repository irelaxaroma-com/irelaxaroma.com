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
                                Wallet
                            </h2>
                        </div>
                        <div style="padding: 16px 0">
                            <h2>Total:  <span style="color: rgb(207, 51, 51)" id="total_cr"></span> <span style="color: rgb(207, 51, 51)"> {{ $data['selectedCurrencySymbol'] }} </span>
                            </h2>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12 px-0 media-main" id="points-template">

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
                '/api/client/wallet';
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
                        html += "<div class='col-md-3' style='padding:6px !important'><b>Date</b></div>";                           
                        html += "<div class='col-md-3' style='padding:6px !important'><b>Description</b></div>";
                        html += "<div class='col-md-3' style='padding:6px !important'><b>Debit Amount</b></div>";
                        html += "<div class='col-md-3' style='padding:6px !important'><b>Credit Amount</b></div>";

                        for (i = 0; i < data.data.length; i++) {
                            html += "<div class='col-md-3' style='padding:6px !important'>" + data.data[i].date + "</div>";
                            html += "<div class='col-md-3' style='padding:6px !important'>" + data.data[i].transaction_description + "</div>";
                            html += "<div class='col-md-3' style='padding:6px !important'>" + data.data[i].debit_amount + "</div>";
                            html += "<div class='col-md-3' style='padding:6px !important'>" + data.data[i].credit_amount + "</div>";
                        }
                        html += "</div>";
                        $('#points-template').html(html);

                        var total = data.data[0].total_credit - data.data[0].total_debit;
                        $('#total_cr').html(total);
                    }
                },
                error: function(data) {},
            });
        }
        
    </script>
@endsection
