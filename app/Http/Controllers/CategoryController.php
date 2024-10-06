<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;

class CategoryController extends SearchableController
{
    private string $title = 'Category';


    function getQuery(): Builder
    {
        return Category::orderBy('code');
    }

    function list(ServerRequestInterface $request): View
    {
        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search)->withCount('products');

        return view('categories.list', [
            'title' => "{$this->title}: List",
            'search' => $search,
            'categories' => $query->paginate(self::ITEMS_PER_PAGE),
        ]);
    }

    function show(string $categoryCode): View
    {
        $category = $this->find($categoryCode);

        return view('categories.view', [
            'title' => "{$this->title}: View",
            'category' => $category,
        ]);
    }


    function showCreateForm(): View
    {
        return view('categories.create-form', [
            'title' => "{$this->title}: Create",
        ]);
    }

    public function create(ServerRequestInterface $request): RedirectResponse {
        $data = $request->getParsedBody();
        
        try {
            $category = new Category();
            $category->fill($data);
            $category->save();
            
            return redirect()->route('categories.list')->with('status', "Category {$category->name} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2], // แสดงข้อความข้อผิดพลาด
            ]);
        }
    }



    function showUpdateForm(string $categoryCode): View
    {
        $category = $this->find($categoryCode);

        return view('categories.update-form', [
            'title' => "{$this->title}: Update",
            'category' => $category,
        ]);
    }

    public function update(string $categoryCode, ServerRequestInterface $request): RedirectResponse {
        $data = $request->getParsedBody();
        
        try {
            $category = $this->find($categoryCode);
            $category->fill($data);
            $category->save();
            
            return redirect()->route('categories.view', ['category' => $category->code])
                             ->with('status', "Category {$category->name} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจาก errorInfo[2]
            ]);
        }
    }

    
    public function delete(string $categoryCode): RedirectResponse {
        try {
            $category = $this->find($categoryCode);
            $category->delete();
            
            return redirect()->route('categories.list')->with('status', "Category {$category->name} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจาก errorInfo[2]
            ]);
        }
    }


    function showProducts(
        string $categoryCode,
        ServerRequestInterface $request,
        ProductController $productController,
    ): View {
        $category = $this->find($categoryCode);
        $query = $category->products();
        $search = $productController->prepareSearch($request->getQueryParams());
        $query = $productController->filter($query,$search);

        return view('categories.view-products',[
            'title' =>"{$this->title} {$category->code} : Products",
            'category' => $category,
            'search' => $search,
            'products' => $query->paginate($productController::ITEMS_PER_PAGE),
        ]);
    }



    function showAddProductsForm(
        string $categoryCode,
        ServerRequestInterface $request,
        ProductController $productController,
    ): View {
        $category = $this->find($categoryCode);
    
        $query = Product::whereDoesnthave('category',function (Builder $innerQuery) use ($category):void {
            $innerQuery->where('code',$category->code);
        } )->with ('category');

        $search = $productController->prepareSearch($request->getQueryParams());
        $query = $productController->filter($query,$search);

        return view('categories.add-products-form',[
            'title' =>"{$this->title} {$category->code} : Add Products",
            'category' => $category,
            'search' => $search,
            'products' => $query->paginate($productController::ITEMS_PER_PAGE),
        ]);
    }

    function addProduct (
        string $categoryCode, 
        ServerRequestInterface $request,
    ):   RedirectResponse {
        $category = $this->find($categoryCode);

        $data = $request->getParsedBody();
        $product = Product::whereDoesnthave('category',function (Builder $innerQuery) use ($category):void {
            $innerQuery->where('code',$category->code);
        })->where('code',$data['product'])->firstOrFail();

        $category->products()->save($product);

        return redirect()
        ->back()
        ->with('status',"Product {$product->code} was added to {$category->code}.");
    }
}
    


