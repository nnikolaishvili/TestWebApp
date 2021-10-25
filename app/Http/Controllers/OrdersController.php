<?php

namespace App\Http\Controllers;

use App\Http\Requests\{SearchRequest, Orders\UpdateRequest};
use App\Models\{Order, OrderStatus};
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersController extends Controller
{
    /**
     * View of the orders
     *
     * @param SearchRequest $request
     * @return View
     */
    public function index(SearchRequest $request): View
    {
        $validated = $request->validated();
        $searchValue = $validated['search'] ?? null;
        $query = Order::with('status:id,name');
        if ($searchValue) {
            $query = $query->where('order_id', 'like', "%$searchValue%");
        }

        return view('orders.index', [
            'searchValue' => $searchValue,
            'orders' => $query->paginate(10)->appends(['search' => $searchValue])
        ]);
    }

    /**
     * Export of the orders
     *
     * @param SearchRequest $request
     * @return BinaryFileResponse
     */
    public function export(SearchRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();
        $search = $validated['search'] ?? null;
        $query = Order::with('status');
        if ($search) {
            $query = $query->where('order_id', 'like', "%$search%");
        }
        $orders = $query->get();
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

        return Response::download($filename, $filename, ['Content-Type', 'text/csv']);
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
        $statuses = OrderStatus::pluck('name', 'id')->toArray();

        return view('orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update the status of the order
     *
     * @param Order $order
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Order $order, UpdateRequest $request): RedirectResponse
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
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->back();
    }
}
