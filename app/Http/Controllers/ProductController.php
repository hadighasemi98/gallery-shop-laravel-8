<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\products\AddProductRequest;
use App\Http\Requests\admin\products\UpdateProductsRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;

class ProductController extends Controller
{
    private $categories ;
    private $products ;
    private $addResult ;

    public function __construct()
    {
        $this->categories = Category::all();
        $this->products   = Product::paginate(10);
    }

    public function index()
    {
        $data = [
            'products'   => $this->products,
            'categories' => $this->categories
        ];
        return view('frontend.panel.products.all', $data);
    }

    public function add_form()
    {
        $categories = $this->categories;
        return view('frontend.panel.products.add', compact('categories'));
    }

    public function added(AddProductRequest $request)
    {
        $validData = $request->validated();
        $admin = User::where('role', 'admin')->first();

        $addResult = Product::create([
            'title'         => $validData['title'],
            'price'         => $validData['price'],
            'description'   => $validData['description'],
            'category_id'   => $validData['category_id'],
            'owner_id'      => $admin->id,
        ]);

        return $this->uploadImages($addResult , $validData);

        if (!$addResult) {
            return back()->with('failed' , 'محصول اضافه نشد');
        }

        return back()->with('success' , 'محصول اضافه شد');
    }
    
    
    public function delete($product_id)
    {
        $product = $this->products->find($product_id)->delete();

        if(!$product) { return back()->with('failed' , 'محصول پاک نشد'); }

        return back()->with('success','با موفقیت پاک شد');
    }

    public function edit($product_id)
    {
        
        $categories = $this->categories;   
        $products = Product::findOrFail($product_id);   
        return view('frontend.panel.products.edit',compact('categories','products') );
    }

    public function update(UpdateProductsRequest $request , $product_id)
    {
        $productId = Product::findOrFail($product_id); 
        $validData = $request->validated();

        $updatedProduct = $productId->update([
            'title'         =>  $validData['title'], 
            'price'         =>  $validData['price'],
            'description'   =>  $validData['description'],
            'category_id'   =>  $validData['category_id'],
        ]);

        if(!$updatedProduct){
            return back()->with('failed', 'محصول بروزرسانی نشد') ;
        }

        return $this->uploadImages($productId , $validData);
        
    }


    public function uploadImages($createdProduct , $validData)
    {

        try {

            $basePath = 'products/' . $createdProduct->id . '/'  ;

            $sourceImagePath = null ;
            $data = [];

            if (isset( $validData['source_url'] ))
            {
                $sourceImagePath = $basePath . 'source_url_' . $validData['source_url']->getClientOriginalName();
                ImageUploader::upload($validData['source_url'], $sourceImagePath , 'local_storage');
                $data += ['source_url' => $sourceImagePath ];
            }

            if (isset( $validData['demo_url'] ))
            {
                $demo_url = $validData['demo_url'] ;
                $demo_urlPath = $basePath . 'demo_url_' . $demo_url->getClientOriginalName();

                ImageUploader::upload($demo_url, $demo_urlPath , 'public_storage');

                $data += ['demo_url' => $demo_urlPath ];
            }
            
            if (isset( $validData['thumbnail_url'] ))
            {
                $thumbnail_url = $validData['thumbnail_url'] ;
                $thumbnail_urlPath = $basePath . 'thumbnail_url_' . $thumbnail_url->getClientOriginalName();
                
                ImageUploader::upload($thumbnail_url, $thumbnail_urlPath , 'public_storage');
                
                $data += ['thumbnail_url' => $thumbnail_urlPath ];
            }

            $updatedProduct = $createdProduct->update($data);

            if(!$updatedProduct){
                throw new \Exception('تصاویر آپدیت نشدند');
            }

            return back()->with('success', 'تصاویر با موفقیت بروزرسانی شد');
            
        }catch(\Exception $e ) {
            return back()->with('failed', $e->getMessage() . ' in line : ' . $e->getLine());
        }
    }

    public function download_demo($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(public_path($product->demo_url));
    }

    public function download_source($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(storage_path('app/local_storage/'.$product->source_url));
    }
}
