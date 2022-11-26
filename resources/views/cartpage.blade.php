@extends('layouts.master')
@section('content')
    @include(isset(getSetting()['cart_page']) ? 'includes.cartpages.cart-'.getSetting()['cart_page'] :
    'includes.cartpages.cart-style1')
    <input type="hidden" id="totalItems" value="0" />

@endsection
@section('script')
    <script>
        languageId = $.trim(localStorage.getItem("languageId"));
        cartSession = $.trim(localStorage.getItem("cartSession"));
        if (cartSession == null || cartSession == 'null') {
            cartSession = '';
        }
        loggedIn = $.trim(localStorage.getItem("customerLoggedin"));
        customerToken = $.trim(localStorage.getItem("customerToken"));

        $(document).ready(function() {
            if (loggedIn == '1') {
                cartItem('');
                menuCart('');
            } else {
                cartItem(cartSession);
                menuCart(cartSession)
            }
        });

        


      


        function couponCartItem() {
            coupon_code = $.trim($("#coupon_code").val());
            if (coupon_code == '') {
                toastr.error('{{ trans("coupon-code-required") }}');
                price = $(".caritem-subtotal").attr('price-symbol');
                $(".caritem-discount-coupon").html('');
                localStorage.setItem("couponCart", '');
                $(".caritem-grandtotal").html(price);
                return;
            }

            if($.trim($("#totalItems").val()) == '0'){
                toastr.error('{{ trans("cart-is-empty") }}');
                return;
            }
            
            $.ajax({
                type: 'post',
                url: "{{ env('url') }}" + '/api/client/coupon?currency='+localStorage.getItem("currency"),
                data: {
                    coupon_code: coupon_code,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + customerToken,
                },
                beforeSend: function() {},
                success: function(data) {
                    $("#coupon_code").val(coupon_code);
                    if (data.status == 'Success') {
                        if (data.data.type == 'fixed') {
                            subtotal = $(".caritem-subtotal").attr('price');
                            discount = data.data.amount * data.data.currency.exchange_rate;
                            subtotal = subtotal - discount;
                        } else {
                            subtotal = $(".caritem-subtotal").attr('price');
                            discount = (subtotal / 100) * data.data.amount;
                            subtotal = subtotal - discount;
                        }    
                            if(data.data.currency != '' && data.data.currency != 'null' && data.data.currency != null){
                                
                                if(data.data.currency.symbol_position == 'left'){
                                    $(".caritem-discount-coupon").html(data.data.currency.code +''+ discount.toFixed(2));
                                    $(".caritem-grandtotal").html(data.data.currency.code +''+ subtotal.toFixed(2));
                                } else {
                                    $(".caritem-discount-coupon").html(discount.toFixed(2) +''+ data.data.currency.code);
                                    $(".caritem-grandtotal").html(subtotal.toFixed(2) +''+ data.data.currency.code);
                                }
                                
                                $(".caritem-discount-coupon").attr('price', discount);
                                
                            }
                            

                        localStorage.setItem("couponCart", coupon_code);
                        toastr.success("{{ trans('lables.cart-coupon-applied') }}");
                    } else {
                        price = $(".caritem-subtotal").attr('price-symbol');
                        $(".caritem-discount-coupon").html('');
                        $(".caritem-grandtotal").html(price);
                        localStorage.setItem("couponCart", '');
                        toastr.error('{{ trans("invalid-coupon") }}');
                    }
                },
                error: function(data) {
                    price = $(".caritem-subtotal").attr('price-symbol');
                    $(".caritem-discount-coupon").html('');
                    $(".caritem-grandtotal").html(price);
                    localStorage.setItem("couponCart", '');
                    if (data.status == 401) {
                        // toastr.error(data.res
                        toastr.error('{{ trans('response.please_login_first') }}')
                    }
                },
            });
        }


        function updateCartItem() {
            len = $(".cartItem-row").length;
            for (i = 0; i < len; i++) {
                product_id = $(".cartItem-row").eq(i).attr('product_id');
                qty = $(".cartItem-row").eq(i).find('.cartItem-qty').val();

                product_type = $(".cartItem-row").eq(i).attr('product_type');
                product_combination_id = '';
                if (product_type == 'variable') {
                    if ($.trim($(".cartItem-row").eq(i).attr('product_combination_id')) == '' || $.trim($(".cartItem-row")
                            .eq(i).attr('product_combination_id')) == 'null') {
                        toastr.error('{{ trans("combination-missing") }}');
                        return;
                    }
                    product_combination_id = $(".cartItem-row").eq(i).attr('product_combination_id');
                }

                addToCartFun(product_id, product_combination_id, cartSession, qty);
            }

            cartItem(cartSession);
            couponCart = $.trim(localStorage.getItem("couponCart"));
            if (couponCart != 'null' && couponCart != '') {
                $("#coupon_code").val(couponCart);
                couponCartItem();
            }
        }


        $(document).on('click', '.quantity-right-plus', function() {
            var row_id = $(this).attr('data-field');

            var quantity = $('#quantity' + row_id).val();
            $('#quantity' + row_id).val(parseInt(quantity) + 1);
        })

        $(document).on('click', '.quantity-left-minus', function() {
            var row_id = $(this).attr('data-field');
            var quantity = $('#quantity' + row_id).val();
            if (quantity > 1)
                $('#quantity' + row_id).val(parseInt(quantity) - 1);
        })
    </script>
@endsection
