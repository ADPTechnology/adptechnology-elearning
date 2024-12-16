<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\HistoryService;
use Illuminate\Http\Request;

class AdminPurchaseHistoryController extends Controller
{
    private $historyService;

    public function __construct(HistoryService $service)
    {
        $this->historyService = $service;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->historyService->getDataTableAdmin();
        }

        return view('admin.history.index');
    }


    public function show(Order $order)
    {

        $order->load([
            'user',
            'products',
            'products.orderable',
            'products.orderable.file' => fn($q) => $q->where('category', 'plans')
        ]);

        $total = $order->products->sum('unit_price');

        return view('admin.history.show', compact('order', 'total'));
    }
}
