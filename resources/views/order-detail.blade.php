@extends('layouts.master')
@section('content')

<!-- Shop Page One content -->
<div class="container-fuild">
    <nav aria-label="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">{{ trans('lables.bread-crumb-home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('lables.order-detail') }}</li>
            </ol>
        </div>
    </nav>
</div>


<!--My Order Content -->
<section class="order-two-content pro-content">
    <div class="container">
        <div class="page-heading-title">
            <h2> {{ trans('lables.order-detail-heading') }}
            </h2>

        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-12 col-lg-3 ">
                <div class="heading">
                    <h2>
                        {{ trans('lables.order-detail-my-account') }}
                    </h2>
                    <hr>
                </div>

                @include('includes.side-menu')
            </div>
            <div class="col-12 col-lg-9 ">


                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="heading">
                            <h2>
                                <small>
                                    {{ trans('lables.order-detail-order-id') }}#<span class="order-no"></span>
                                </small>
                            </h2>
                            <hr>
                        </div>

                        <table class="table order-id">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-6 col-md-6"><strong>{{ trans('lables.order-detail-order-status') }}</strong></td>
                                    <td class="col-6 col-md-6 order-status"></td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-6 col-md-6">{{ trans('lables.order-detail-order-date') }}</td>
                                    <td class="underline col-6 col-md-6 order-date"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-12 col-md-6">
                        <div class="heading">
                            <h2>
                                <small>
                                    {{ trans('lables.order-detail-payment-shipping-method') }}
                                </small>
                            </h2>
                            <hr>
                        </div>

                        <table class="table order-id">
                            <tbody>
                                {{-- <tr class="d-flex">
                                    <td class="col-6">Shipping Method</td>
                                    <td class="col-6 order-shipping-method"></td>
                                </tr> --}}
                                <tr class="d-flex">
                                    <td class="col-6">{{ trans('lables.order-detail-transaction-id') }}</td>
                                    <td class="underline col-6 order-transaction_id"></td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-6">{{ trans('lables.order-detail-order-amount') }}</td>
                                    <td class="underline col-6 order-amount"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    
                </div>

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="heading">
                            <h2>
                                <small>
                                    {{ trans('lables.order-detail-billing-detail') }}
                                </small>
                            </h2>
                            <hr>
                        </div>

                        <table class="table order-id">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="address col-12 order-billing-address"></td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="address col-12 order-billing-detail"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="col-12 col-md-6">
                        <div class="heading">
                            <h2>
                                <small>
                                    {{ trans('lables.order-detail-shipping-detail') }}
                                </small>
                            </h2>
                            <hr>
                        </div>

                        <table class="table order-id">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="address col-12 col-md-6 order-delivery-address"></td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="address col-12 col-md-12 order-delivery-detail"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="col-12 col-md-12">
                        <div class="heading">
                            <h2>
                                <small>
                                    {{ trans('lables.checkout-order-notes-title') }}
                                </small>
                            </h2>
                            <hr>
                        </div>

                        <table class="table order-id">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="address col-12 col-md-12 customer-order-notes"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    
                </div>

                <table class="table items">

                    <thead>
                        <tr class="d-flex">
                            <th class="col-2"></th>
                            <th class="col-3">{{ trans('lables.order-detail-item') }}</th>
                            <th class="col-2">{{ trans('lables.order-detail-price') }}</th>
                            <th class="col-2">{{ trans('lables.order-detail-discountprice') }}</th>
                            <th class="col-1">{{ trans('lables.order-detail-qty') }}</th>
                            <th class="col-2">{{ trans('lables.order-detail-subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody id="order-show-detail">
                    </tbody>
                </table>

                <table class="table order-id">
                    <tbody>
                        
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-subtotal') }}</td> 
                            <td class="col-2 right bold order-subtotal"></td>
                        </tr>
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-discount') }}</td> 
                            <td class="col-2 right order-discount"></td>
                        </tr>
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-tax') }}</td> 
                            <td class="col-2 right order-tax"></td>
                        </tr>
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-shipping') }}</td> 
                            <td class="col-2 right order-shipping"></td>
                        </tr>
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-coupon-discount') }}</td> 
                            <td class="col-2 right coupon-amount"></td>
                        </tr>
                        
                        <tr>
                            <td class="col-7"></td>
                            <td class="col-3 bold">{{ trans('lables.order-detail-total') }}</td> 
                            <td class="col-2 right bold order-total"></td>
                        </tr>
                         
                        
                        </tbody>
                    </table>

                    
                    
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="comments-area">
                                <div class="form-group">
                                    <label for="comment">{{ trans('lables.order-detail-comments') }}</label>
                                    <div id="show_comment">

                                    </div>
                                    <textarea class="form-control" id="comment" rows="3" placeholder="{{ trans('lables.order-detail-comment') }}"></textarea>
                                    <button type="button" class="btn btn-secondary swipe-to-top mt-2" id="saveComments" onclick="saveComments()">Save Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>


                <!-- ............the end..... -->
            </div>
        </div>
    </div>
</section>

<template id="order-show-detail-template">
    <tr class="d-flex responsive-lay">
        <td class="col-12 col-md-2">
            <img class="img-fluid order-image" src="" alt="John Doe" class="mr-3">
        </td>
        <td class="col-12 col-md-3 item-detail-left bold order-product-name"></td>
        <td class="col-12 col-md-2 order-price"></td>
        <td class="col-12 col-md-2 order-discountprice"></td>
        <td class="col-12 col-md-1 order-qty"></td>
        <td class="col-12 col-md-2 order-sub-price bold"></td>
    </tr>
</template>

@endsection
@section('script')
<script>
    loggedIn = $.trim(localStorage.getItem("customerLoggedin"));
    if (loggedIn != '1') {
        window.location.href = "{{url('/')}}";
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

    $(document).ready(function() {
        getCustomerOrder();
    });

    function getCustomerOrder() {
        id = '{{$id}}';
        $.ajax({
            type: 'get',
            url: "{{ env('url') }}" +
                '/api/client/customer/order/' + id + '?orderDetail=1&language_id=' + languageId + '&productDetail=1&billing_country=1&billing_state=1&delivery_country=1&delivery_state=1&currency='+localStorage.getItem("currency"),
            headers: {
                'Authorization': 'Bearer ' + customerToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
            },
            beforeSend: function() {},
            success: function(data) {
                if (data.status == 'Success') {
                    const templ = document.getElementById("order-show-detail-template");
                    $("#order-show-detail").html('');
                    order = data.data.order_date.split('T');
                    $(".order-date").html(order[0]); 
                    $(".order-no").html(data.data.order_id);
                    $(".order-status").html(data.data.order_status);

                    $(".order-delivery-address").html(data.data.delivery_street_aadress);

                    $(".customer-order-notes").html(data.data.customer_order_notes);
                    city = state = postcode = country =  '';
                    
                    if(data.data.delivery_city != null && data.data.delivery_city != 'null' && data.data.delivery_city != ''){
                        city = data.data.delivery_city;
                    }
                    
                    if(data.data.delivery_postcode != null && data.data.delivery_postcode != 'null' && data.data.delivery_postcode != ''){
                        postcode = ', '+data.data.delivery_postcode;
                    }

                    if(data.data.delivery_country1 != null && data.data.delivery_country1 != 'null' && data.data.delivery_country1 != ''){
                        country = ', '+data.data.delivery_country1.country_name;
                    }
                    if(data.data.delivery_state1 != null && data.data.delivery_state1 != 'null' && data.data.delivery_state1 != ''){
                        state = ', '+data.data.delivery_state1.name;
                    }
                    
                    detail_address = city + state + postcode + country;
                    
                    $(".order-delivery-detail").html(detail_address);


                    $(".order-billing-address").html(data.data.billing_street_aadress);
                    city = state = postcode = country =  '';
                    
                    if(data.data.billing_city != null && data.data.billing_city != 'null' && data.data.billing_city != ''){
                        city = data.data.billing_city;
                    }
                    
                    if(data.data.billing_postcode != null && data.data.billing_postcode != 'null' && data.data.billing_postcode != ''){
                        postcode = ', '+data.data.billing_postcode;
                    }

                    if(data.data.billing_country1 != null && data.data.billing_country1 != 'null' && data.data.billing_country1 != ''){
                        country = ', '+data.data.billing_country1.country_name;
                    }
                    if(data.data.billing_state1 != null && data.data.billing_state1 != 'null' && data.data.billing_state1 != ''){
                        state = ', '+data.data.billing_state1.name;
                    }
                    detail_address = city + state + postcode + country;

                    $(".order-billing-detail").html(detail_address);
                    
                    $(".order-transaction_id").html((data.data.transaction_id != null && data.data.transaction_id != 'null' && data.data.transaction_id != '')?data.data.transaction_id:"N/A");

                    
                    
                    // $(".order-shipping-method").html(data.data.shipping_method);

                    
                    // clone.querySelector(".order-product-name").setAttribute('href', '/orders/' + data.data.order_id);
                    
                    subtotal = total_discount = coupon_amount = 0.00;

                    if (data.data.order_detail != null && data.data.order_detail != 'null' && data.data.order_detail != '') {
                        for (k = 0; k < data.data.order_detail.length; k++) {
                            const clone = templ.content.cloneNode(true);
                            if (data.data.order_detail[k].product != null && data.data.order_detail[k].product != 'null' && data.data.order_detail[k].product != '') {
                                if (data.data.order_detail[k].product.product_type == 'variable') {
                                    if (data.data.order_detail[k].product_combination.gallary != null && data.data.order_detail[k].product_combination.gallary != 'null' && data.data.order_detail[k].product_combination.gallary != '') {
                                        clone.querySelector(".order-image").setAttribute('src',
                                            '/gallary/' + data.data.order_detail[k].product_combination.gallary.gallary_name);
                                        clone.querySelector(".order-image").setAttribute('alt', data.data.order_detail[k].product_combination.gallary.gallary_name);
                                        name = data.data.order_detail[k].product.detail[0].title +' - ';
                                        for (loop = 0; loop < data.data.order_detail[k].product_combination.combination
                                            .length; loop++) {
                                            if (data.data.order_detail[k].product_combination.combination.length - 1 == loop) {
                                                name += data.data.order_detail[k].product_combination.combination[loop].variation.detail[0].name;
                                            } else {
                                                name += data.data.order_detail[k].product_combination.combination[loop].variation.detail[0].name + '-';
                                            }
                                        }
                                        clone.querySelector(".order-product-name").innerHTML = name;
                                    }
                                } else {
                                    if (data.data.order_detail[k].product.detail != null && data.data.order_detail[k].product.detail != 'null' && data.data.order_detail[k].product.detail != '') {
                                        clone.querySelector(".order-image").setAttribute('src',
                                            '/gallary/' + data.data.order_detail[k].product.product_gallary.gallary_name);
                                        
                                            clone.querySelector(".order-image").setAttribute('alt', data.data.order_detail[k].product.product_gallary.gallary_name);

                                        clone.querySelector(".order-product-name").innerHTML = data.data.order_detail[k].product.detail[0].title;

                                    }
                                }
                            }
                            
                            price = data.data.order_detail[k].product_price;
                            discountprice = (data.data.order_detail[k].product_discount > 0) ? +data.data.order_detail[k].product_discount : "-";
                            sub_total = data.data.order_detail[k].product_total * data.data.order_detail[k].product_qty;
                            subtotal = subtotal + data.data.order_detail[k].product_price * data.data.order_detail[k].product_qty;

                            if (data.data.coupon_amount != null && data.data.coupon_amount != 'null' && data.data.coupon_amount != '') {
                                coupon_amount = data.data.coupon_amount;    
                            }     

                            if (data.data.order_detail[k].product_discount > 0)
                            {
                                total_discount = total_discount + ((parseFloat(data.data.order_detail[k].product_price) - parseFloat(data.data.order_detail[k].product_discount)) * parseFloat(data.data.order_detail[k].product_qty));
                            }
                                if (data.data.currency_id.symbol_position == 'left') {
                                    sub_total = data.data.currency_id.code +''+ sub_total.toFixed(2);
                                    price = data.data.currency_id.code +''+ price;
                                    if (discountprice != "-")
                                        discountprice = data.data.currency_id.code +''+ discountprice;
                                }
                                else{
                                    sub_total = sub_total.toFixed(2) +''+ data.data.currency_id.code;
                                    price = price +''+ data.data.currency_id.code;
                                    if (discountprice != "-")
                                        discountprice = data.data.currency_id.code +''+ discountprice;
                                }
                            

                            clone.querySelector(".order-price").innerHTML = price;
                            clone.querySelector(".order-discountprice").innerHTML = discountprice;
                            clone.querySelector(".order-qty").innerHTML = data.data.order_detail[k].product_qty;
                            clone.querySelector(".order-sub-price").innerHTML = sub_total;

                            $("#order-show-detail").append(clone);
                        }
                    }

                    if (data.data.order_comments != null && data.data.order_comments != 'null' && data.data.order_comments != '') {
                        for (i =0; i < data.data.order_comments.length; i++) {
                            console.log(data.data.order_comments);
                            if (data.data.order_comments[i].user === null) {
                                $('#show_comment').append("<div class='row justify-content-center'><div class='col-12 col-md-8'><div class='d-flex align-items-center border-0 p-3 rounded  mb-5'><div class='media'><div class='media-body ml-3'><div class='d-flex justify-content-between mb-1'><h3 class='font-size-h3 text-dark info-tag font-weight-n'>"+data.data.order_comments[i].customer.customer_first_name+"</h3></div><div class='badge btn-secondary font-size-h5 rounded'>Customer</div></div></div><div class='text-input position-relative disabled'><div class='date-text'><h5 class='font-size-h5 text-black-50 text-right'>"+data.data.order_comments[i].created_at+"</h5></div><fieldset class='form-label-group'><textarea class='form-control fixed-size'id='horizontalTextarea1' rows='6' placeholder='comment.message' disabled>"+data.data.order_comments[i].message+"</textarea></fieldset></div></div></div></div>")

                            } else {
                                $('#show_comment').append("<div class='row justify-content-center'><div class='col-12 col-md-8'><div class='d-flex align-items-center border-0 p-3 rounded  mb-5'><div class='media'><div class='media-body ml-3'><div class='d-flex justify-content-between mb-1'><h3 class='font-size-h3 text-dark info-tag font-weight-n'>"+data.data.order_comments[i].user.name+"</h3></div><div class='badge btn-secondary font-size-h5 rounded'>"+data.data.order_comments[i].user.role.name+"</div></div></div><div class='text-input position-relative disabled'><div class='date-text'><h5 class='font-size-h5 text-black-50 text-right'>"+data.data.order_comments[i].created_at+"</h5></div><fieldset class='form-label-group'><textarea class='form-control fixed-size'id='horizontalTextarea1' rows='6' placeholder='comment.message' disabled>"+data.data.order_comments[i].message+"</textarea></fieldset></div></div></div></div>")
                            }
                        }
                    }
                    
                    subtotal = parseFloat(subtotal).toFixed(2);
                    total_discount = parseFloat(total_discount).toFixed(2);

                    if (data.data.currency_id.symbol_position == 'left') {
                        $(".order-amount").html(data.data.currency_id.code+''+data.data.order_price);
                        $(".order-subtotal").html(data.data.currency_id.code+''+subtotal);
                        $(".order-tax").html(data.data.currency_id.code+''+data.data.total_tax);
                        $(".order-shipping").html(data.data.currency_id.code+''+data.data.shipping_cost);
                        $(".order-discount").html('-'+data.data.currency_id.code+''+total_discount);
                        $(".order-total").html(data.data.currency_id.code+''+data.data.order_price);
                        $(".coupon-amount").html('-'+data.data.currency_id.code+''+coupon_amount);
                    } else {
                        $(".order-amount").html(data.data.order_price+''+data.data.currency_id.code);
                        $(".order-subtotal").html(subtotal+''+data.data.currency_id.code);
                        $(".order-tax").html(data.data.total_tax+''+data.data.currency_id.code);
                        $(".order-shipping").html(data.data.shipping_cost+''+data.data.currency_id.code);
                        $(".order-discount").html('-'+total_discount+''+data.data.currency_id.code);
                        $(".order-total").html(data.data.order_price+''+data.data.currency_id.code);
                        $(".coupon-amount").html('-'+coupon_amount+''+data.data.currency_id.code);
                    }
                    // clone.querySelector(".shipping-address-listing-delete-btn").setAttribute('data-id', data.data[i].id);
                    // clone.querySelector(".shipping-address-listing-delete-btn").setAttribute('onclick', 'shippingDelete(this)');
                }
            },
            error: function(data) {},
        });
    }

    function saveComments(){
        id = '{{$id}}';
        comment = $('#comment').val();

        var page_url = "/api/web/add_comments";
        let data = {
            comment:comment,
            id:id
        }

        $.ajax({
                type: 'post',
                url: "{{ env('url') }}" + '/api/client/add_comments',
                headers: {
                    'Authorization': 'Bearer ' + customerToken,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                data:data,
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        $('#show_comment').empty();
                        getCustomerOrder();
                        toastr.success('Your Comments are Sent')
                        $('#comment').val('')
                    }
                },
                error: function(data) {
                    toastr.error(data.responseJSON.errors.comment[0])
                    console.log(data.responseJSON.errors.comment[0],'if error')
                },
            });    
    }
</script>
@endsection