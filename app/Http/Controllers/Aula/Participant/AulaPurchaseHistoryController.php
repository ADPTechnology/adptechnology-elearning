<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\HistoryService;
use Illuminate\Http\Request;

class AulaPurchaseHistoryController extends Controller
{
    private $historyService;

    public function __construct(HistoryService $service)
    {
        $this->historyService = $service;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->historyService->getDataTable();
        }

        return view('aula.viewParticipant.history.index');
    }


    public function show(Order $order)
    {

        $order->load([
            'products',
            'products.orderable',
            'products.orderable.file' => fn($q) => $q->where('category', 'plans')
        ]);

        $total = $order->products->sum('unit_price');

        return view('aula.viewParticipant.history.show', compact('order', 'total'));
    }
}
