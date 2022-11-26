<?php

namespace App\Repository\Web;

use App\Contract\Web\WalletInterface;
use App\Http\Resources\Web\Wallet as WalletResource;
use App\Models\Admin\TransactionDetail;
use App\Traits\ApiResponser;
use Auth;

class WalletRepositroy implements WalletInterface
{
    use ApiResponser;
    public function all($params)
    {
        try {
            // $sql = Point::findStatus('0');
            // dd(Auth::id());
            $sql = new TransactionDetail;
            if (\Request::route()->getName() == 'customer.wallet') {
                if (Auth::check()) {
                    $sql = $sql->ReferenceId(Auth::id());
                }
            }

            if (isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0) {
                    $numOfResult = $_GET['limit'];
            } else {
                    $numOfResult = 100;
            }

            $sql = $sql->paginate($numOfResult);
            return $this->successResponse(WalletResource::collection($sql), 'Data Get Successfully!');

        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

}
