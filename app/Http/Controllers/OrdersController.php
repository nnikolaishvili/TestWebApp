<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\ExportRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersController extends Controller
{
    /**
     * View of the orders
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Order::with('status:id,name');
        $searchValue = $request->search;
        if ($request->has('search') && !empty($searchValue)) {
            $query = $query->where('order_id', 'like', "%$request->search%");
        }

        return view('orders.index', [
            'searchValue' => $searchValue,
            'ordersCount' => $query->count(),
            'orders' => $query->paginate(10)->appends(['search' => $searchValue])
        ]);
    }

    /**
     * Export of the orders
     *
     * @param ExportRequest $request
     * @return BinaryFileResponse
     */
    public function export(ExportRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();
        $query = Order::with('status');
        $orders = $validated['search'] ?
            $query->where('order_id', 'like', '%' . $validated['search'] . '%')->get() :
            $query->get();

        $filename = 'export.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, Order::TABLE_HEADERS);
        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_id,
                $order->total,
                $order->date,
                $order->status->name
            ]);
        }
        fclose($handle);
        $headers = ['Content-Type', 'text/csv'];

        return Response::download($filename, $filename, $headers);
    }

    /**
     * Show page of the order
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Edit page of the order
     *
     * @param Order $order
     * @return View
     */
    public function edit(Order $order): View
    {
        $statuses = Status::pluck('name', 'id')->toArray();

        return view('orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update the status of the order
     *
     * @param Order $order
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Order $order, UpdateRequest $request)
    {
        $order->update($request->validated());

        return redirect()->route('orders.index');
    }

    /**
     * Delete order
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back();
    }
}
