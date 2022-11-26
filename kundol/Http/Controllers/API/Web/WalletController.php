<?php

namespace App\Http\Controllers\API\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contract\Web\WalletInterface;

class WalletController extends Controller
{
    private $WalletRepository;

    public function __construct(WalletInterface $WalletRepository)
    {
        $this->WalletRepository = $WalletRepository;
    }

    public function index(Request $request)
    {
        return $this->WalletRepository->all($request);
    }
}
