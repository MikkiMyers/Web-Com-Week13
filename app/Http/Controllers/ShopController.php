<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;

class ShopController extends SearchableController
{
    private string $title = 'Shop';

    #[\Override]
    function getQuery(): Builder
    {
        return Shop::orderby('code');
    }

    #[\Override]
    function filterByTerm(Builder|Relation $query, ?string $term): Builder|Relation
    {
        if (!empty($term)) {
            foreach (preg_split('/\s+/', trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word): void {
                    $innerQuery
                        ->orWhere('code', 'LIKE', "%{$word}%")
                        ->orWhere('name', 'LIKE', "%{$word}%")
                        ->orWhere('owner', 'LIKE', "%{$word}%")
                        ;
                });
            }
        }

        return $query;
    }

    function list(ServerRequestInterface $request): View
    {
        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search)->withCount('products');

        return view('shops.list', [
            'title' => $this->title,
            'search' => $search,
            'shops' => $query->paginate(self::ITEMS_PER_PAGE),
        ]);
    }

    function show(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.view', [
            'title' => $this->title,
            'shop' => $shop,
        ]);
    }

    function showCreateForm(): View
    {
        return view('shops.create-form', [
            'title' => 'Create Shop',
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        try {
            // สร้างร้านค้าใหม่จากข้อมูลที่ได้รับจาก request
            $shop = Shop::create($request->getParsedBody());
    
            // Redirect ไปที่รายการร้านค้า พร้อมข้อความสถานะ
            return redirect()
                ->route('shops.list')
                ->with('status', "Shop {$shop->code} was created.");
        } catch (\Illuminate\Database\QueryException $excp) {
            // จัดการข้อผิดพลาดฐานข้อมูลและส่งกลับไปยังหน้าเดิมพร้อมข้อความข้อผิดพลาด
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจากฐานข้อมูล
            ]);
        }
    }

  


    function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.update-form', [
            'title' => "{$this->title} Update",
            'shop' => $shop,
        ]);
    }

    function update(string $shopCode, ServerRequestInterface $request): RedirectResponse {
        try {
            $shop = $this->find($shopCode);
            $shop->fill($request->getParsedBody());
            $shop->save();
    
            return redirect()->route('shops.view', ['shop' => $shop->code])
                ->with('status', "Shop {$shop->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2], // ส่งข้อความข้อผิดพลาดจากฐานข้อมูล
            ]);
        }
    }

    function delete(string $shopCode): RedirectResponse {
        try {
            $shop = $this->find($shopCode);
            $shop->delete();
    
            return redirect()->route('shops.list')
                ->with('status', "Shop {$shop->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2], // ส่งข้อความข้อผิดพลาดจากฐานข้อมูล
            ]);
        }
    }
    public function showProducts(
        string $shopCode, 
        ServerRequestInterface $request,
        ProductController $productController
    ): View {
        $shop = $this->find($shopCode);
    
        if (!$shop) {
            return redirect()->route('shops.index')->with('error', 'Shop not found.');
        }
    
        $query = $shop->products()->with('category');
        $search = $productController->prepareSearch($request->getQueryParams());
        $query = $productController->filter($query, $search);
    
        return view('shops.view-products', [
            'title' => "{$this->title} {$shop->code} : Products",
            'shop' => $shop,
            'search' => $search,
            'products' => $query->paginate(5), // ใช้ paginate เพื่อแบ่งหน้า
        ]);
    }

    function showAddProductsForm(
        string $shopCode, 
        ServerRequestInterface $request,
        ProductController $productController,
    ): View {
        $shop = $this->find($shopCode);

        $query = Product::whereDoesnthave('shops', function (Builder $innerQuery) use ($shop): void {
            $innerQuery->where('code', $shop->code);
        })-> with('category');

        $search = $productController->prepareSearch($request->getQueryParams());
        $query = $productController->filter($query,$search);
            
        return view('shops.add-products-form', [
            'title' => "{$this->title} Add Products",
            'shop' => $shop,
            'search' => $search,
            'products' => $query->paginate($productController::ITEMS_PER_PAGE),
        ]);
    }

    function addProduct(
        string $shopCode,
        ServerRequestInterface $request
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        
        $data = $request->getParsedBody();
        $product = Product::whereDoesntHave('shops', function (Builder $innerQuery) use ($shop): void {
            $innerQuery->where('code', $shop->code);
        })->where('code', $data['product'])->firstOrFail();
        
        $shop->products()->attach($product);
        
        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was added to Shop {$shop->code}.")
            ;
    }

    function removeProduct(
        string $shopCode,
        string $productCode
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        
        $product = $shop->products()->where('code', $productCode)->firstOrFail();
        
        $shop->products()->detach($product);
        
        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was removed from Shop {$shop->code}.")
        ;
    }
}
