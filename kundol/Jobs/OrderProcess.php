<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Resources\Web\Order as OrderResource;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\OrderProcessed;
use App\Models\Admin\Account;
use App\Models\Admin\Currency;
use App\Models\Admin\DefaultAccount;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodSetting;
use App\Models\Admin\ShippingMethod;
use App\Models\Admin\TaxRate;
use App\Models\Admin\Transaction;
use App\Models\Admin\TransactionDetail;
use App\Models\Admin\Setting;
use App\Models\Web\OrderHistory;
use App\Services\Admin\OrderService;
use App\Models\Web\Order;
use App\Traits\Transactions;
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use Razorpay\Api\Api;

class OrderProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiResponser , Transactions;
    public $gateway;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $parms;
    public function __construct($parms)
    {
        $this->parms = $parms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \DB::beginTransaction();
            $orderService = new OrderService;
                
            $amount = 0;
            if (isset($this->parms['ispos']) && !empty($this->parms['ispos']) || isset($this->parms['is_add_sale']) && !empty($this->parms['is_add_sale'])) {
                $customer_id = $this->parms['customer_id'];
            } else {   
                $customer_id = \Auth::id();
            }

            if (isset($this->parms['warehouse_id']) && !empty($this->parms['warehouse_id'])) {
                $warehouse_id = $this->parms['warehouse_id'];
            } else {   
                $warehouse_id = "1";
            }

            
            $currency = Currency::defaultCurrency()->select('exchange_rate', 'symbol_position', 'code')->first();
            $settings = Setting::where('type', 'business_general')->get();
            $normalizdSetting = [];
            foreach($settings as $setting){
                $normalizdSetting[$setting->key] = $setting->value; 
            }
            
            if($customer_id == '' || $customer_id == null){
                $customer_id = $this->parms['customer_id'];
            }
            
            $stockValidate = $orderService->CheckStock($customer_id, $warehouse_id);
            if ($stockValidate['status'] == 'Error')
                return $stockValidate;

            if (isset($this->parms['coupon_code'])) {
                $couponValidate = $orderService->CouponCodeValidation($this->parms['coupon_code'], $customer_id, $customer_id);
                if ($couponValidate['status'] == 'Error')
                    return $couponValidate;
                $sql = $couponValidate['data'];
                if ($sql->type == 'percentage') {
                    $amount = ($stockValidate['data'] / 100) * $sql->amount;
                } else {
                    $amount = $sql->amount;
                }
                $this->parms['coupon_amount'] = $amount * $currency->exchange_rate;
            }
            $this->parms['order_price'] = $stockValidate['data'];
            $this->parms['order_price'] = $this->parms['order_price'] * $currency->exchange_rate;
            $this->parms['order_price'] = $this->parms['order_price'] - $amount;

            $this->parms['order_status'] = isset($this->parms['order_status']) ? $this->parms['order_status'] : 'Pending';
            
            // return $this->parms['order_status'];
            $tax_rate = TaxRate::findByState($this->parms['delivery_state'])->get();
            $total = 0;
            foreach($tax_rate as $tax_rates){
                $total = $total + ($stockValidate['data'] / 100) * $tax_rates->tax_rate;
            }
            $subtotal = $this->parms['order_price'];
            $total = $total * floatVal($currency->exchange_rate);
            $shippingMethodPrice = ShippingMethod::where('is_default', '1')->first();
            $shipping_method_price = 0;
            
            if (isset($this->parms['ispos']) && !empty($this->parms['ispos']) || isset($this->parms['is_add_sale']) && !empty($this->parms['is_add_sale'])) {
                
                $shipping_method_price = 0;
            
            } else if ($subtotal > $normalizdSetting['free_shipping_order_price']) {
                
                $shipping_method_price = 0;
                            
            } else {
                
                if($shippingMethodPrice){
                    if ($shippingMethodPrice->methods_type_link == 'shippingByWeight')
                    {
                        $shipping_method_price = $this->parms['total_by_weight'] * $shippingMethodPrice->amount * $currency->exchange_rate;
                    } else {
                        $shipping_method_price = $shippingMethodPrice->amount * $currency->exchange_rate;
                    }    
                }
            
            }

               
            if (isset($this->parms['ispos']) && !empty($this->parms['ispos'])) {
                $this->parms['order_from'] = 'pos';
            } elseif (isset($this->parms['is_add_sale']) && !empty($this->parms['is_add_sale'])) {
                $this->parms['order_from'] = 'addsale';
            } elseif (isset($this->parms['is_web']) && !empty($this->parms['is_web'])) {
                $this->parms['order_from'] = 'web';
            } elseif (isset($this->parms['is_ios']) && !empty($this->parms['is_ios'])) {
                $this->parms['order_from'] = 'iosapp';
            } else {   
                $this->parms['order_from'] = 'androidapp';
            }
            $this->parms['warehouse_id'] = $warehouse_id;
            $this->parms['shipping_method'] = $shippingMethodPrice->methods_type_link;
            $this->parms['total_tax'] = $total;
            $this->parms['shipping_cost'] = $shipping_method_price;
            $this->parms['order_price'] = $this->parms['order_price'] + $total + $shipping_method_price;
            $this->parms['customer_id'] = $customer_id;
            $this->parms['currency_id'] = $currency->id;
            $this->parms['currency_value'] = $currency->exchange_rate;
            $sql = Order::create($this->parms);
            OrderHistory::create([
                'order_id'=>$sql->id,
                'order_status' =>  $this->parms['order_status']
            ]);

            
        } catch (Exception $e) {
            return $this->errorResponse();
        }
        if ($sql) {
            
            if($this->parms['payment_method'] == 'stripe'){
                $paymentMethod = $orderService->paymentMethod($this->parms['payment_method'], $this->parms['cc_number'], $this->parms['cc_expiry_month'], $this->parms['cc_expiry_year'], $this->parms['cc_cvc'], $this->parms['order_price']);

                if ($paymentMethod['status'] == 'Error')
                    return $paymentMethod;
                
                if($paymentMethod['message'] == 'Success'){
                    $orderService->orderTransectionCredit($sql);
                    $sql->payment_status = 'Success';
                }
            }
            else if($this->parms['payment_method'] == 'paypal'){
                $this->gateway = Omnipay::create('PayPal_Pro');
                $payment = PaymentMethodSetting::where('payment_method_id',1)->get();
                $this->gateway->setUsername(isset($payment[0]->value) ? $payment[0]->value : '');
                $this->gateway->setPassword(isset($payment[1]->value) ? $payment[1]->value : '');
                $this->gateway->setSignature(isset($payment[2]->value) ? $payment[2]->value : '');
                $this->gateway->setTestMode(true);
                $formData = array(
                    'firstName' => $this->parms['billing_first_name'],
                    'lastName' => $this->parms['billing_last_name'],
                    'number' => $this->parms['cc_number'],
                    'expiryMonth' => $this->parms['cc_expiry_month'],
                    'expiryYear' => $this->parms['cc_expiry_year'],
                    'cvv' => $this->parms['cc_cvc']
                );
        
                try {
                    // Send purchase request
                    $response = $this->gateway->purchase([
                        'amount' => $this->parms['order_price'],
                        'currency' => 'USD',
                        'card' => $formData
                    ])->send();
        
                    // Process response
                    if ($response->isSuccessful()) {
        
                        // Payment was successful
                        $arr_body = $response->getData();
                        $amounts = $arr_body['AMT'];
                        $currencies = $arr_body['CURRENCYCODE'];
                        $transaction_id = $arr_body['TRANSACTIONID'];
        
                        // echo "Payment of $amount $currency is successful. Your Transaction ID is: $transaction_id";
                        $sql->transaction_id = $transaction_id;
                        $sql->payment_status = 'Success';
                        $paymentMethod['message'] = 'Success';
                        $orderService->orderTransectionCredit($sql);
                    } else {
                        // Payment failed
                        $arr_body = $response->getData();
                        $sql->payment_status = 'Failed';
                        $paymentMethod['message'] = $response->getMessage();
                    }
                } catch(Exception $e) {
                    
                }
            }

            else if($this->parms['payment_method'] == 'braintree'){
                $payment = PaymentMethodSetting::where('payment_method_id',5)->get();
                $this->gateway = Omnipay::create('Braintree');
                $this->gateway->setMerchantId(isset($payment[0]->value) ? $payment[0]->value : '');
                $this->gateway->setPublicKey(isset($payment[1]->value) ? $payment[1]->value : '');
                $this->gateway->setPrivateKey(isset($payment[2]->value) ? $payment[2]->value : '');
                $this->gateway->setTestMode(true);
                $nounce = $this->parms['payment_method_nonce'];
                $response = $this->gateway->purchase([
                    'amount' => $this->parms['order_price'],
                    'token' => $nounce 
                ])->send();
                
                if ($response->isSuccessful()) {
                    $arr_body = $response->getData();
                    
                    $sql->transaction_id = isset($arr_body->transaction->id) ? $arr_body->transaction->id : null;
                    $sql->payment_status = 'Success';
                    $paymentMethod['message'] = 'Success';
                    $orderService->orderTransectionCredit($sql);
                } else {
                    $sql->transaction_id = '';
                    $sql->payment_status = 'Failed';
                    $paymentMethod['message'] = '';
                }
            }

            else if($this->parms['payment_method'] == 'authorize_net'){
                $payment = PaymentMethodSetting::where('payment_method_id',6)->get();
                $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
                $this->gateway->setAuthName(isset($payment[0]->value) ? $payment[0]->value : '');
                $this->gateway->setTransactionKey(isset($payment[1]->value) ? $payment[1]->value : '');
                $this->gateway->setTestMode(true);
                $creditCard = new \Omnipay\Common\CreditCard([
                    'number' => $this->parms['cc_number'],
                    'expiryMonth' => $this->parms['cc_expiry_month'],
                    'expiryYear' => $this->parms['cc_expiry_year'],
                    'cvv' => $this->parms['cc_cvc'],
                ]);
      
                // Generate a unique merchant site transaction ID.
                $transactionId = rand(100000000, 999999999);
      
                $response = $this->gateway->authorize([
                    'amount' => $this->parms['order_price'],
                    'currency' => 'USD',
                    'transactionId' => $transactionId,
                    'card' => $creditCard,
                ])->send();
      
                if($response->isSuccessful()) {
                    $transactionReference = $response->getTransactionReference();
                    $response = $this->gateway->capture([
                        'amount' => $this->parms['order_price'],
                        'currency' => 'USD',
                        'transactionReference' => $transactionReference,
                        ])->send();
      
                    $transaction_id = $response->getTransactionReference();
                    $sql->transaction_id = $transaction_id;
                    $sql->payment_status = 'Success';
                    $paymentMethod['message'] = 'Success';
                    $orderService->orderTransectionCredit($sql);
                } else {
                    $sql->transaction_id = '';
                    $sql->payment_status = 'Failed';
                    $paymentMethod['message'] = 'Success';
                }
                
            }
            else if($this->parms['payment_method'] == 'sagepay'){
                $payment = PaymentMethodSetting::where('payment_method_id',7)->get();
                $this->gateway = OmniPay::create('SagePay\Direct');
                $this->gateway->setVendor('your-vendor-code');
                $this->gateway->setTestMode(true);
                $creditCard = new \Omnipay\Common\CreditCard([
                    'number' => $this->parms['cc_number'],
                    'expiryMonth' => $this->parms['cc_expiry_month'],
                    'expiryYear' => $this->parms['cc_expiry_year'],
                    'cvv' => $this->parms['cc_cvc'],
                ]);
      
        
                try {
                    // Send purchase request
                   // Send the request.

                    $request = $this->gateway->createCard([
                        'currency' => 'USD',
                        'card' => $creditCard,
                    ]);
                    $response = $request->send();
                   
                    // Process response
                    if ($response->isSuccessful()) {
        
                        $cardReference = $response->getCardReference();
                        $sql->transaction_id = $cardReference;
                        $sql->payment_status = 'Success';
                        $paymentMethod['message'] = 'Success';
                        $orderService->orderTransectionCredit($sql);
                    } else {
                        $sql->transaction_id = '';
                        $sql->payment_status = 'Failed';
                        $paymentMethod['message'] = '';
                    }
                } catch(Exception $e) {
                    
                }
            }

            else if($this->parms['payment_method'] == 'razorpay'){
                $payment = PaymentMethodSetting::where('payment_method_id',9)->get();
                
                $this->gateway = new Api(isset($payment[1]->value) ? $payment[1]->value : '',isset($payment[2]->value) ? $payment[2]->value : '');
                //$this->gateway->setTestMode(true);
                try {
                    $orderPrice = intval($this->parms['order_price']) * 100;
                    
                    $payment = $this->gateway->payment->fetch($this->parms['razor_pay_transaction_id']);
                    $payment->capture(array('amount'=>$orderPrice));
                    $sql->transaction_id = $this->parms['razor_pay_transaction_id'];
                    $sql->payment_status = 'Success';
                    $paymentMethod['message'] = 'Success';
                    $orderService->orderTransectionCredit($sql);
                } catch(Exception $e) {
                    $sql->transaction_id = '';
                    $sql->payment_status = 'Failed';
                }
            }


           
            $sql->save();
            //dd($sql->id);
            OrderProcessed::dispatch($sql->id);
            \DB::commit();
            return $this->successResponse(new OrderResource($sql), 'Order Save Successfully!');
        } else {
            \DB::rollback();
            return $this->errorResponse();
        }
    }
}
