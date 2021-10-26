<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Jobs\FetchProductsJob;
use App\Models\Product;
use App\Services\Storeden\StoredenProductsService;
use App\Traits\ImageProcessingTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductsController extends Controller
{
    use ImageProcessingTrait;

    public function index(SearchRequest $request)
    {
        $validated = $request->validated();
        $searchValue = $validated['search'] ?? null;
        $products = Product::search($searchValue)
            ->paginate(Product::ITEMS_PER_PAGE)
            ->appends(['search' => $searchValue]);

        return view('products.index', [
            'searchValue' => $searchValue,
            'products' => $products
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
        $products = Product::search($validated['search'] ?? null)->get();
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
                $product->image_full_path
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
     * @throws Exception
     */
    public function show(Product $product): View
    {
        $this->checkProductImage($product);

        return view('products.show', compact('product'));
    }

    /**
     * Edit page of the product
     *
     * @param Product $product
     * @return View
     * @throws Exception
     */
    public function edit(Product $product): View
    {
        $this->checkProductImage($product);

        return view('products.edit', compact('product'))->with([
            'statuses' => Product::STATUSES
        ]);
    }

    /**
     * Update the status of the order
     *
     * @param Product $product
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Product $product, UpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (isset($validated['image_file'])) {
            $validated['image_url'] = $this->storeImage("images/products/$product->id", $validated['image_file']);
        }
        $product->update($validated);

        return redirect()->route('products.index');
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

    /**
     * Refresh table - fetch products from API and replace with the old ones
     *
     * @return RedirectResponse
     */
    public function fetch(): RedirectResponse
    {
        dispatch_sync(new FetchProductsJob);

        return redirect()->route('products.index');
    }

    /**
     * If product has no image, we fetch it from the API
     *
     * @throws Exception
     */
    private function checkProductImage($product)
    {
        if (!$product->image_url) {
            $response = (new StoredenProductsService())->fetchProductImages($product->uid);
            if ($response && $response['images'] && count($response['images'])) {
                $product->update([
                    'image_url' => $response['images'][0]['thumbnail']
                ]);
            }
        }
    }
}
