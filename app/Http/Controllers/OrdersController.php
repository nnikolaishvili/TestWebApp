<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\UpdateRequest;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('status:id,name');
        $searchValue = $request->search;
        if ($request->has('search') && !empty($searchValue)) {
            $query = $query->where('order_id', 'like', "%$request->search%");
        }

        return view('orders.index', [
            'ordersCount' => $query->count(),
            'orders' => $query->paginate(10)->appends(['search' => $searchValue])
        ]);
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $statuses = Status::pluck('name', 'id')->toArray();

        return view('orders.edit', compact('order', 'statuses'));
    }

    public function update(Order $order, UpdateRequest $request)
    {
        $order->update($request->validated());

        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back();
    }
}
