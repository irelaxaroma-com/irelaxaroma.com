<?php

namespace App\Http\Controllers\Web;

use DB;
use Carbon\Carbon;
use App\Models\Admin\Tax;
use App\Models\Web\Order;
use App\Models\Web\Point;
use App\Jobs\OrderProcess;
use App\Models\Admin\Page;
use App\Models\Admin\Brand;
use App\Models\DemoSettings;
use Illuminate\Http\Request;
use App\Models\Admin\Account;
use App\Models\Admin\Product;
use App\Models\Admin\Setting;
use App\Models\Admin\BlogNews;
use App\Models\Admin\Customer;
use App\Models\Admin\Supplier;
use App\Models\Admin\Attribute;
use App\Services\Web\HomeService;
use App\Models\Admin\BlogCategory;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\ProductDetail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Admin\DefaultAccount;
use App\Services\Admin\OrderService;
use Illuminate\Support\Facades\Http;
use App\Services\Admin\AccountService;
use App\Models\Admin\ProductCombination;
use App\Models\Admin\PaymentMethodSetting;
use Academe\AuthorizeNet\ServerRequest\Payload\Payment;
use App\Models\Admin\TransactionDetail;
use Auth;

class IndexController extends Controller
{
    public function getcall()
    {
        $url = "https://eu-test.oppwa.com/v1/payments";
        $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
            "&amount=92.00" .
            "&currency=EUR" .
            "&paymentBrand=VISA" .
            "&paymentType=DB" .
            "&card.number=4200000000000000" .
            "&card.holder=Jane Jones" .
            "&card.expiryMonth=05" .
            "&card.expiryYear=2034" .
            "&card.cvv=123";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }
    public function Index()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('home', compact('data', 'setting'));
    }

    public function contactUs()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        return view('contactus', compact('data'));
    }

    public function aboutUs()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        return view('aboutus', compact('data'));
    }

    public function productDetail($product, $slug)
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        return view('product-detail', compact('data', 'product'));
    }

    public function shop()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $attribute = new Attribute;
        $languageId = $data['selectedLenguage'];
        $attribute = $attribute->getAttributeDetailByLanguageId($languageId);
        $attribute = $attribute->getVariationDetailByLanguageId($languageId);
        $attribute = $attribute->get();
        $brand = Brand::all();
        $data['attribute'] = $attribute;
        $data['price_range'] = ['0-500', '500-1000', '1000-2000'];
        $data['brand'] = $brand;
        return view('shop', compact('data'));
    }

    public function cartPage()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('cartpage', compact('data', 'setting'));
    }

    public function login()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('login', compact('data', 'setting'));
    }

    public function loginwithsocial()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('loginwithsocial', compact('data', 'setting'));
    }

    public function blogDetail($slug)
    {

        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('blog.blog-detail', compact('data', 'setting', 'slug'));
    }

    public function blog()
    {

        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();

        return view('blog.blog', compact('data', 'setting'));
    }




    public function checkout()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        $payment_method_setting = PaymentMethodSetting::where('payment_method_id', '3')->get();
        $payment_method = PaymentMethod::where('status', '1')->get();
        $payment_method_default = PaymentMethod::whereNotIn('id', ['3', '4'])->where('default', '1')->get();
        return view('checkout', compact('data', 'setting', 'payment_method', 'payment_method_default', 'payment_method_setting'));
    }

    public function wishlist()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('wishlist', compact('data', 'setting'));
    }
    public function compare()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('compare', compact('data', 'setting'));
    }

    public function profile()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('profile', compact('data', 'setting'));
    }

    public function points()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        $totalPoint = Point::findStatus('0')->sum('points');
        return view('points', compact('data', 'setting', 'totalPoint'));
    }

    public function wallet()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('wallet', compact('data', 'setting'));
    }

    public function thankyou()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('thankyou', compact('data', 'setting'));
    }


    public function changePassword()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('change-password', compact('data', 'setting'));
    }

    public function forgetPassword()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('forget-password', compact('data', 'setting'));
    }

    public function resetPassword()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('reset-password', compact('data', 'setting'));
    }



    public function shippingAddress()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('shipping-address', compact('data', 'setting'));
    }

    public function orders()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('orders', compact('data', 'setting'));
    }

    public function ordersDetail($id)
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('order-detail', compact('data', 'setting', 'id'));
    }

    public function term()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('term', compact('data', 'setting'));
    }

    public function refund()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('refund', compact('data', 'setting'));
    }

    public function privacy()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        return view('privacy', compact('data', 'setting'));
    }


    public function page($slug)
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();

        $languageId = $data['selectedLenguage'];

        $page = new Page;
        $page = $page->getPageDetailByLanguageId($languageId);
        $page = $page->where('slug', $slug);
        $page = $page->first();

        // return $page;
        return view('page', compact('data', 'setting', 'page'));
    }


    public function orderStats()
    {

        $totalOrders = Order::all();
        $thisYearOrders = Order::whereYear('created_at', date('Y'))->get();
        $lastYearOrders = Order::whereYear('created_at', now()->subYear()->year)
            ->get();
        $thisWeekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();


        $totalProducts = Product::all();
        $thisYearProducts = Product::whereYear('created_at', date('Y'))->get();
        $lastYearProducts = Product::whereYear('created_at', now()->subYear()->year)
            ->get();
        $thisWeekProducts = Product::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();


        $totalCustomers = Customer::all();
        $thisYearCustomers = Customer::whereYear('created_at', date('Y'))->get();
        $lastYearCustomers = Customer::whereYear('created_at', now()->subYear()->year)
            ->get();
        $thisWeekCustomer = Customer::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $totalSales = Order::where('order_status', 'Complete')->sum('order_price');
        $thisYearSales = Order::where('order_status', 'Complete')->whereYear('created_at', date('Y'))->sum('order_price');
        $lastYearSales = Order::where('order_status', 'Complete')->whereYear('created_at', now()->subYear()->year)
            ->sum('order_price');

        $thisWeekSales = Order::where('order_status', 'Complete')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('order_price');

        $customerMonthly = Customer::select(DB::raw('count(id) as `id`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('MONTHNAME(created_at) month'), DB::raw('MONTH(created_at) monthNumber'))
            ->groupby('month')
            ->whereYear('created_at', date('Y'))
            ->orderBy('monthNumber', 'ASC')
            ->get();



        $saleMonthly = Order::select(DB::raw('sum(order_price) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('MONTHNAME(created_at) month'), DB::raw('MONTH(created_at) monthNumber'))
            ->where('order_status', 'Complete')
            ->groupby('month')
            ->whereYear('created_at', date('Y'))
            ->orderBy('monthNumber', 'ASC')
            ->get();


        return [
            'totalOrders' => count($totalOrders), 'thisYearOrders' => count($thisYearOrders), 'lastYearOrders' => count($lastYearOrders),
            'totalProducts' => count($totalProducts), 'thisYearProducts' => count($thisYearProducts), 'lastYearProducts' => count($lastYearProducts),
            'totalCustomers' => count($totalCustomers), 'thisYearCustomers' => count($thisYearCustomers), 'lastYearCustomers' => count($lastYearCustomers), 'lastYear' => now()->subYear()->year,

            'totalSales' => $totalSales, 'thisYearSales' => $thisYearSales, 'lastYearSales' => $lastYearSales,
            'thisWeekOrders' => $thisWeekOrders, 'thisWeekProducts' => $thisWeekProducts, 'thisWeekCustomer' => $thisWeekCustomer,
            'thisWeekSales' => $thisWeekSales, 'customerMonthly' => $customerMonthly, 'saleMonthly' => $saleMonthly

        ];
    }


    public function paytmPayment(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'mobile' => 'required|numeric|digits:10',
        //     'address' => 'required',
        // ]);
        $parms = $request->all();
        $payment_method_setting = PaymentMethodSetting::where('payment_method_id', '10')->get();

        $payment = \PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $parms['order_id'],
            'user' => $parms['user_id'],
            'mobile_number' => $parms['mobile'],
            'email' => $parms['email'], // your user email address
            'amount' => $parms['charges'], // amount will be paid in INR.
            'callback_url' => url('paytm_response') // callback URL
        ]);


        return $payment->receive();
    }

    public function paytmResponse()
    {
        $transaction = \PaytmWallet::with('receive');

        //$response = $transaction->response();

        $order_id = $transaction->getOrderId(); // return a order id

        $transaction_id = $transaction->getTransactionId(); // return a transaction id

        // update the db data as per result from api call
        if ($transaction->isSuccessful()) {
            // Paytm::where('order_id', $order_id)->update(['status' => 1, 'transaction_id' => $transaction->getTransactionId()]);
            
            $transaction->getResponseMessage(); // Get Response Message If Available

            $transaction->getOrderId(); // Get order id
            Order::where('id', $order_id)->update([
                'payment_status' => 'Success',
                'transaction_id' => $transaction_id
            ]);

            return redirect('/thankyou');

        } else if ($transaction->isFailed()) {
            // Paytm::where('order_id', $order_id)->update(['status' => 0, 'transaction_id' => $transaction->getTransactionId()]);
            return "Your payment is failed.";
        } else if ($transaction->isOpen()) {
            // Paytm::where('order_id', $order_id)->update(['status' => 2, 'transaction_id' => $transaction->getTransactionId()]);
            return "Your payment is processing.";
        }
        
    }
    public function orderWebView()
    {
        $homeService = new HomeService;
        $data = $homeService->homeIndex();
        $setting = getSetting();
        $payment_method_setting = PaymentMethodSetting::where('payment_method_id', '3')->get();
        $payment_method = PaymentMethod::where('status', '1')->get();
        $payment_method_default = PaymentMethod::whereNotIn('id', ['3', '4'])->where('default', '1')->get();
        return view('order-web-view-checkout', compact('data', 'setting', 'payment_method', 'payment_method_default', 'payment_method_setting'));
    }


    public function molliePayment($order_id)
    {

        $order = Order::where('id', $order_id)->first();

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "USD",
                "value" => strval($order->order_price) // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => strval($order->id),
            "redirectUrl" => url('/thankyou'),
            "webhookUrl"  => url('/mollie-webhook'),
            "metadata" => [
                "order_id" => strval($order->id),
            ],
        ]);

        return redirect($payment->getCheckoutUrl());
    }

    public function mollieWebHook(Request $request)
    {
        $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get($paymentId);
        $order = Order::where('id', $payment->description)->first();
        $order->payment_status = "Success";
        $order->transaction_id = $paymentId;
        $order->payment_method = 'mollie';
        $order->save();
        Log::info('Payment received.' . (string)$payment->description);
    }

    public function handleGatewayCallback(Request $request)
    {
        $payment = PaymentMethodSetting::where('payment_method_id', 12)->get();
        $response = Http::withHeaders([
            "Authorization" => isset($payment[1]->value) ? "Bearer " . $payment[1]->value : '',
            "Cache-Control" => "no-cache",
        ])->get("https://api.paystack.co/transaction/verify/" . $_GET['trxref']);

        if ($response['data']['status']) {
            $paymentId = $response['data']['id'];
            $order = Order::where('id', $response['data']['metadata']['order_id'])->first();
            $order->payment_status = "Success";
            $order->transaction_id = $paymentId;
            $order->payment_method = 'Paystack';
            $order->save();
        }

        Log::info('Payment received from Paystack.' . (string)$response['data']['status']);
        return redirect('thankyou');
    }


    public function updateSettingsByUser(Request $request)
    {
        $isExisted = DemoSettings::where('ip', \Request::ip())->first();
        if ($isExisted) {
            DemoSettings::where('ip', \Request::ip())->update([
                'color' => $request->color,
                'header_style' => $request->header_style,
                'footer_style' => $request->footer_style,
                'slider_style' => $request->slider_style,
                'banner_style' => $request->banner_style,
                'cart_style' => $request->cart_style,
                'product_page_style' => $request->product_page_style,
                'shop_style' => $request->shop_style,
            ]);
        } else {
            $demoSettings = new DemoSettings;
            $demoSettings->color = $request->color;
            $demoSettings->header_style = $request->header_style;
            $demoSettings->footer_style = $request->footer_style;
            $demoSettings->slider_style = $request->slider_style;
            $demoSettings->banner_style = $request->banner_style;
            $demoSettings->cart_style = $request->cart_style;
            $demoSettings->product_page_style = $request->product_page_style;
            $demoSettings->shop_style = $request->shop_style;
            $demoSettings->color = $request->color;

            $demoSettings->ip = \Request::ip();
            $demoSettings->save();
        }
        return redirect()->back();
    }

    public function ResetDemoSettings()
    {
        DemoSettings::where('ip', \Request::ip())->delete();
        return redirect()->back();
    }


    public function seedFromExistingData()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Account::truncate();
        DefaultAccount::truncate();
        DefaultAccount::insertOrIgnore(
            [
                'id' => '1',
                'account_id' => 8,
                'type' => 'customer',
            ]
        );
        DefaultAccount::insertOrIgnore(
            [
                'id' => '2',
                'account_id' => 11,
                'type' => 'vendor',
            ]
        );

        

        DefaultAccount::insertOrIgnore(
            [
                'id' => '3',
                'account_id' => 6,
                'type' => 'supplies',
            ]
        );

        DefaultAccount::insertOrIgnore(
            [
                'id' => '4',
                'account_id' => 2,
                'type' => 'cash',
            ]
        );


        DefaultAccount::insertOrIgnore(
            [
                'id' => '5',
                'account_id' => 10,
                'type' => 'accountpayable',
            ]
        );

        DefaultAccount::insertOrIgnore(
            [
                'id' => '6',
                'account_id' => 14,
                'type' => 'income',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '1',
                'name' => 'ASSETS',
                'code' => '01',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '0',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '2',
                'name' => 'CASH',
                'code' => '0101',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '3',
                'name' => 'BANK',
                'code' => '0102',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '4',
                'name' => 'PAYPAL',
                'code' => '0103',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '5',
                'name' => 'STRIPE',
                'code' => '0104',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );
        Account::insertOrIgnore(
            [
                'id' => '6',
                'name' => 'SUPPLIES',
                'code' => '0105',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '7',
                'name' => 'ACCOUNT RECIEVEABLE',
                'code' => '0106',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '1',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '8',
                'name' => 'CUSTOMER',
                'code' => '010601',
                'account_type' => 'Assets',
                'status' => '1',
                'parent_id' => '7',
            ]
        );




        Account::insertOrIgnore(
            [
                'id' => '9',
                'name' => 'LIABILITY',
                'code' => '02',
                'account_type' => 'Liability',
                'status' => '1',
                'parent_id' => '0',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '10',
                'name' => 'ACCOUNT PAYABLE',
                'code' => '0201',
                'account_type' => 'Liability',
                'status' => '1',
                'parent_id' => '9',
            ]
        );

        Account::insertOrIgnore(
            [
                'id' => '11',
                'name' => 'VENDOR',
                'code' => '020101',
                'account_type' => 'Liability',
                'status' => '1',
                'parent_id' => '10',
            ]
        );



        Account::insertOrIgnore(
            [
                'id' => '12',
                'name' => 'EXPENSE',
                'code' => '03',
                'account_type' => 'Expense',
                'status' => '1',
                'parent_id' => '0',
            ]
        );


        Account::insertOrIgnore(
            [
                'id' => '13',
                'name' => 'COST OF PURCHASE',
                'code' => '0301',
                'account_type' => 'Expense',
                'status' => '1',
                'parent_id' => '12',
            ]
        );


        Account::insertOrIgnore(
            [
                'id' => '14',
                'name' => 'income',
                'code' => '04',
                'account_type' => 'income',
                'status' => '1',
                'parent_id' => '14',
                'reference_id' => '14',
                'type' => 'income',


            ]
        );

        $customers = Customer::all();
        foreach ($customers as $customer) {
            $accounts = new AccountService;
            $accounts->createAccount('CUSTOMER', $customer->first_name . ' ' . $customer->last_name, $customer->id, 'customer');
        }

        $suppliers = Supplier::all();
        foreach ($suppliers as $supplier) {
            $accounts = new AccountService;
            $accounts->createAccount('vendor', $supplier->first_name . ' ' . $supplier->last_name, $supplier->id, 'supplier');
        }

        $taxes = Tax::all();
        foreach ($taxes as $tax) {
            $accounts = new AccountService;
            $accounts->createAccount('accountpayable', $tax->title, $tax->id, 'tax');
        }


        $products = Product::where('product_type', 'simple')->with('detail')->get();
        foreach ($products as $product) {
            $accounts = new AccountService;
            $accounts->createAccount('SUPPLIES', $product->detail[0]->title, $product->id, 'simple_product');
        }

        $productCombinations = ProductCombination::all();

        foreach ($productCombinations as $productCombination) {
            $product_title =  ProductDetail::where('product_id',$productCombination->product_id)->first();
            $accounts = new AccountService;
            $accounts->createAccount('SUPPLIES',$product_title->title. variationProductTitleBasedOnCombinationId($productCombination->id), $productCombination->id, 'variable_product');
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
