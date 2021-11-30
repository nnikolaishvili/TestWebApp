<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Jobs\FetchProductsJob;
use App\Models\Product;
use App\Models\ProductImage;
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

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:view-products');
        $this->middleware('can:refresh-tables')->only('fetch');
    }

    /**
     * Products table view
     *
     * @param SearchRequest $request
     * @return View
     */
    public function index(SearchRequest $request): View
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
        fputcsv($handle, ['uid', 'title', 'price', 'final_price', 'code', 'status', 'image_url']);

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

        return view('products.edit')->with([
            'product' => $product->load('images'),
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
        $product->update($validated);

        if (isset($validated['images'])) {
            foreach ($validated['images'] as $id => $image) {
                ProductImage::find($id)->update([
                    'image_url' => $this->storeImage("images/products/$product->id", $image)
                ]);
            }
        }

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
        if (!$product->images()->count()) {
            $response = (new StoredenProductsService())->fetchProductImages($product->uid);

            if ($response && $response['images'] && count($response['images'])) {
                $images = [];
                foreach ($response['images'] as $image) {
                    $images[] = [
                        'product_id' => $product->id,
                        'image_url' => $image['thumbnail'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ProductImage::insert($images);
            }
        }
    }
}
