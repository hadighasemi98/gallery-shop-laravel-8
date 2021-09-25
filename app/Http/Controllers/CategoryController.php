<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\AddCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('created_at', 'DESC')->get();
        $data = ['category' => $category ];

        return view('frontend.panel.categories.categories',$data);
    }
    
    public function add(AddCategoryRequest $request)
    {
        $validData = $request->validated();

        $result = Category::create([
            'title' => $validData['title'],
            'slug'  => $validData['slug']
        ]);

        if (!$result) 
        {
            return back()->with('failed','دسته بندی اضافه نشد ') ;
        }
          
        return back()->with('success','دسته بندی اضافه شد ');
    }



}
