<?php

namespace App\Http\Controllers;

use App\Jobs\FetchOrdersJob;
use App\Http\Requests\{SearchRequest, Orders\UpdateRequest};
use App\Models\{Order, OrderStatus};
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:export-order')->only('export');
        $this->middleware('can:refresh-tables')->only('fetch');
        $this->middleware('can:modify-order')->only('edit', 'update',' cancel');
    }

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
        $orders = Order::with('status:id,name')
            ->search($searchValue)->paginate(Order::ITEMS_PER_PAGE)
            ->appends(['search' => $searchValue]);

        return view('orders.index', [
            'searchValue' => $searchValue,
            'orders' => $orders
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
        $orders = Order::with('status:id,name')->search($validated['search'] ?? null)->get();
        $filename = 'export.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['order ID', 'total', 'date', 'status']);

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
     * Cancel order
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function cancel(Order $order): RedirectResponse
    {
        $order->cancel();

        return redirect()->back();
    }

    /**
     * Refresh table - fetch orders from API and replace with the old ones
     *
     * @return RedirectResponse
     */
    public function fetch(): RedirectResponse
    {
        dispatch_sync(new FetchOrdersJob);

        return redirect()->route('orders.index');
    }
}
