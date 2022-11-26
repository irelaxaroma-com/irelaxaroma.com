@extends('layouts.master')

@section('script')
<script>
    loggedIn = $.trim(localStorage.getItem("customerLoggedin"));
    if(loggedIn == '1'){
        window.location.href = "{{url('/')}}";
    }

    @if(isset($_GET["token"]) && $_GET["token"] != '')
        
        token = "{{ isset($_GET['token']) ? $_GET['token'] : '' }}";
        hash = "{{ isset($_GET['hash']) ? $_GET['hash'] : '' }}";
        customer_id = "{{ isset($_GET['customer_id']) ? $_GET['customer_id'] : '' }}";
        email = "{{ isset($_GET['email']) ? $_GET['email'] : '' }}";
        first_name = "{{ isset($_GET['first_name']) ? $_GET['first_name'] : '' }}";
        last_name = "{{ isset($_GET['token']) ? $_GET['last_name'] : '' }}";

        localStorage.setItem("customerToken",token);
        localStorage.setItem("customerHash",hash);
        localStorage.setItem("customerId",customer_id);
        localStorage.setItem("customerEmail",email);
        localStorage.setItem("customerFname",first_name);
        localStorage.setItem("customerLname",last_name);
        localStorage.setItem("customerLoggedin",'1');
        localStorage.setItem("cartSession",'');
        window.location.href = "/";
                
    @endif
</script>
@endsection