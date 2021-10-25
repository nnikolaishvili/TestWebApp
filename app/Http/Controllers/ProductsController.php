<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductsController extends Controller
{
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();
        $searchValue = $validated['search'] ?? null;
        $query = Product::query();
        if ($searchValue) {
            $query = $query->where('title', 'like', "%$searchValue%");
        }

        return view('products.index', [
            'searchValue' => $searchValue,
            'products' => $query->paginate(10)->appends(['search' => $searchValue])
        ]);
    }

    /**
     * Export of the products
     *
     * @param SearchRequest $request
     * @return BinaryFileResponse
     */
    public function export(SearchRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();
        $search = $validated['search'] ?? null;
        $query = Product::query();
        if ($search) {
            $query = $query->where('title', 'like', '%' . $validated['search'] . '%');
        }
        $products = $query->get();
        $filename = 'export.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, Product::TABLE_HEADERS);
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->uid,
                $product->title,
                $product->price,
                $product->final_price,
                $product->code,
                $product->status_name,
                $product->image_url
            ]);
        }
        fclose($handle);

        return Response::download($filename, $filename, ['Content-Type', 'text/csv']);
    }

    /**
     * Show page of the product
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Edit page of the product
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'))->with([
            'statuses' => Product::STATUSES
        ]);
    }

    /**
     * Update the status of the order
     *
     * @param Product $product
     * @param UpdateRequest $request
     * @return void
     */
    public function update(Product $product, UpdateRequest $request)
    {
        $validated = $request->validated();
        // TODO
    }

    /**
     * Delete product
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->back();
    }
}
