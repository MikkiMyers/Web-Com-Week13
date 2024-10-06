<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;

class ProductController extends SearchableController
{
    private string $title = 'Product';

    #[\Override]
    function getQuery(): Builder
    {
        return Product::orderBy('code');
    }

    function filterByPrice(
        Builder|Relation $query,
        ?float $minPrice,
        ?float $maxPrice
    ): Builder|Relation {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    #[\Override]
    function prepareSearch(array $search): array
    {
        return [
            ...parent::prepareSearch($search),
            'minPrice' => null,
            'maxPrice' => null,
            ...$search,
        ];
    }

    #[\Override]
    function filter(Builder|Relation $query, array $search): Builder|Relation
    {
        $query = parent::filter($query, $search);
        $query = $this->filterByPrice($query, $search['minPrice'], $search['maxPrice']);

        return $query;
    }

    function list(ServerRequestInterface $request): View
{
    $search = $this->prepareSearch($request->getQueryParams());
    $query = $this->search($search)
        ->with('category')
        ->withCount('shops');

    return view('products.list', [
        'title' => "{$this->title}: List",
        'search' => $search,
        'products' => $query->paginate(self::ITEMS_PER_PAGE),
    ]);
}

function show(string $productCode): View
{
    $product = $this->find($productCode);

    return view('products.view', [
        'title' => "{$this->title}: View",
        'product' => $product,
        
    ]);
}

public function showCreateForm(
    CategoryController $categoryController
): View {
    Gate::authorize('create', Product::class);
    
    $categories = $categoryController->getQuery()->get();
    
    return view('products.create-form', [
        'title' => "{$this->title}: Create",
        'categories' => $categories,
    ]);
}

public function create(ServerRequestInterface $request, CategoryController $categoryController): RedirectResponse {
    Gate::authorize('create', Product::class);
    $data = $request->getParsedBody();

    try {
        $product = new Product();
        $category = $categoryController->find($data['category']);
        unset($data['category']);
        
        $product->fill($data);
        $product->category()->associate($category);
        $product->save();
        
        return redirect()->route('products.list')->with('status', "Product {$product->code} was created.");
    } catch (\Illuminate\Database\QueryException $excp) {
        return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2]]);
    }
}



public function showUpdateForm(
    string $productCode,
    CategoryController $categoryController
): View {
    Gate::authorize('update', Product::class);
    
    $product = $this->find($productCode);
    $categories = $categoryController->getQuery()->get();
    
    return view('products.update-form', [
        'title' => "{$this->title}: Update",
        'product' => $product,
        'categories' => $categories,
    ]);
}

public function update(string $productCode, ServerRequestInterface $request, CategoryController $categoryController): RedirectResponse {
    Gate::authorize('update', Product::class);
    
    $data = $request->getParsedBody();
    
    try {
        $product = $this->find($productCode);
        $category = $categoryController->find($data['category']);
        unset($data['category']);
        
        $product->fill($data);
        $product->category()->associate($category);
        $product->save();
        
        return redirect()->route('products.view', ['product' => $product->code])
                         ->with('status', "Product {$product->code} was updated.");
    } catch (QueryException $excp) {
        return redirect()->back()->withInput()->withErrors([
            'error' => $excp->errorInfo[2], // ส่งข้อผิดพลาดกลับไปที่หน้าเดิม
        ]);
    }
}

public function delete(string $productCode): RedirectResponse {
    Gate::authorize('delete', Product::class);
    
    try {
        // ตรวจสอบว่าพบสินค้าที่ต้องการลบหรือไม่
        $product = $this->find($productCode);
        
        // ลบสินค้า
        $product->delete();
        
        // Redirect กลับไปยังหน้ารายการ พร้อมข้อความสำเร็จ
        return redirect(session()->get('bookmark.products.view', route('products.list')))
            ->with('status', "Product {$product->code} was deleted.");
    } catch (\Illuminate\Database\QueryException $excp) {
        // จัดการข้อผิดพลาด QueryException และส่งข้อความกลับไปยังหน้าเดิม
        return redirect()->back()->withErrors(['error' => $excp->errorInfo[2]]);
    } catch (\Exception $e) {
        // จัดการข้อผิดพลาดอื่นๆ หากมี
        return redirect()->back()->withErrors(['error' => 'An unexpected error occurred while deleting the product.']);
    }
}

    public function showShops(
        string $productCode,
        ServerRequestInterface $request,
        ShopController $shopController
    ): View {
        $product = $this->find($productCode);
        $query = $product->shops();
        $search = $shopController->prepareSearch($request->getQueryParams());
        $query = $shopController->filter($query, $search);
        
        return view('products.view-shops', [
            'title' => "{$this->title} {$product->code}: Shops",
            'product' => $product,
            'search' => $search,
            'shops' => $query->paginate($shopController::ITEMS_PER_PAGE),
        ]);
    }

    public function showAddShopsForm(
        string $productCode,
        ServerRequestInterface $request,
        ShopController $shopController
    ): View {
        Gate::authorize('update', Product::class);
        
        $product = $this->find($productCode);

        $query = Shop::whereDoesntHave('products', function (Builder $innerQuery) use ($product): void {
            $innerQuery->where('code', $product->code);
        });
        
        $search = $shopController->prepareSearch($request->getQueryParams());
        $query = $shopController->filter($query, $search);
        
        return view('products.add-shops-form', [
            'title' => "{$this->title} {$product->code}: Add Shops",
            'search' => $search,
            'product' => $product,
            'shops' => $query->paginate($shopController::ITEMS_PER_PAGE),
        ]);
    }

    public function addShop(
        string $productCode,
        ServerRequestInterface $request
    ): RedirectResponse {
        Gate::authorize('update', Product::class);
    
        $product = $this->find($productCode);
    
        try {
            $data = $request->getParsedBody();
            
            // ค้นหาร้านค้าที่ไม่มีสินค้าตัวนี้
            $shop = Shop::whereDoesntHave('products', function (Builder $innerQuery) use ($product): void {
                $innerQuery->where('code', $product->code);
            })->where('code', $data['shop'])->firstOrFail();
    
            // เพิ่มร้านค้าให้กับสินค้า
            $product->shops()->attach($shop);
    
            // หลังจากสำเร็จ ส่งกลับไปยังหน้าเดิมพร้อมสถานะ
            return redirect()->back()->with('status', "Shop {$shop->code} was added to Product {$product->code}.");
            
        } catch (QueryException $excp) {
            // จัดการข้อผิดพลาดฐานข้อมูล โดยไม่ใช้ withInput()
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจาก errorInfo[2]
            ]);
        }
    }
    
    public function removeShop(
        string $productCode,
        string $shopCode
    ): RedirectResponse {
        Gate::authorize('update', Product::class);
        
        $product = $this->find($productCode);
    
        try {
            // ค้นหาร้านค้าที่ต้องการลบออกจากสินค้า
            $shop = $product->shops()->where('code', $shopCode)->firstOrFail();
    
            // ลบร้านค้าจากสินค้า
            $product->shops()->detach($shop);
    
            // หลังจากลบสำเร็จ ส่งกลับไปยังหน้าเดิมพร้อมสถานะ
            return redirect()->back()->with('status', "Shop {$shop->code} was removed from Product {$product->code}.");
            
        } catch (QueryException $excp) {
            // จัดการข้อผิดพลาดฐานข้อมูล โดยไม่ใช้ withInput()
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจาก errorInfo[2]
            ]);
        }
    }
}
