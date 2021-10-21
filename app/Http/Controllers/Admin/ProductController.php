<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\products\AddProductRequest;
use App\Http\Requests\admin\products\UpdateProductsRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;

class ProductController extends Controller
{
    private $keyForLang = ['key' => 'محصول'];
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


        if (!$addResult) {
            return back()->with('failed', __('conditions.failed_add' , $this->keyForLang) );
        }

        if(! $this->uploadImages($addResult , $validData)){
            return back()->with('failed', __('conditions.products.failed_upload_files'));
        }

        return back()->with('success' , __('conditions.success_add', $this->keyForLang ));


    }
    
    public function delete($product_id)
    {
        $product = $this->products->find($product_id)->delete();
        return back()->with('success', __('conditions.success_delete' , $this->keyForLang));
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
            return back()->with('failed', __('conditions.failed_update' , $this->keyForLang) );
        }

        if(!$this->uploadImages($productId , $validData) ) {
            return back()->with('failed', __('conditions.failed_upload_files') );
        }

        return back()->with('failed', __('conditions.success_update' , $this->keyForLang) );
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
                throw new \Exception(__('conditions.failed_upload_files'));
            }

            return true;
            
        }catch(\Exception $e ) {
            return false;
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
