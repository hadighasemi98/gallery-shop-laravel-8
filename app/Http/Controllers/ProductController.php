<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
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
        $this->products   = Product::all();;
    }

    public function index()
    {
        $data = [
            'products'   => $this->products,
            'categories' => $this->categories
        ];
        return view('frontend.panel.products.all',$data);
    }

    public function add_form()
    {
        $categories = $this->categories;
        return view('frontend.panel.products.add',compact('categories'));
    }

    public function added(AddProductRequest $request)
    {

        $validData = $request->validated();
        $admin = User::where('role' , 'admin')->first();

        $lastId  = $this->products->max('id');
 
        try {
            $basePath = 'products/' . ($lastId + 1) . '/'  ;
            $sourceImagePath = $basePath . 'source_url' . '_' . $validData['source_url']->getClientOriginalName();
            
            $images = [
                'demo_url'      => $validData['demo_url'],
                'thumbnail_url' => $validData['thumbnail_url'],
            ];
            $imagesPath = ImageUploader::multiUploader($images, $basePath);
            ImageUploader::upload($sourceImagePath, $validData['source_url'], 'local_storage');
    

            $addResult = Product::create([
                'title'         => $validData['title'],
                'price'         => $validData['price'],
                'description'   => $validData['description'],
                'category_id'   => $validData['category_id'],
                'demo_url'      => $imagesPath['demo_url'],
                'thumbnail_url' => $imagesPath['thumbnail_url'],
                'source_url'    => $sourceImagePath,
                'owner_id'      => $admin->id,
                ]);

                if (!$addResult) {
                    return back()->with('failed', 'محصول اضافه نشد');
                }
                
            return back()->with('success', ' محصول اضافه شد');

        } catch (\Exception $e) {

            return back()->with('failed',$e->getMessage());
        }

        
        
        // return view('frontend.panel.products.add',compact('categories'));    
    }
}
