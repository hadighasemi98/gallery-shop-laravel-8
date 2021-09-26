<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\AddCategoryRequest;
use App\Http\Requests\admin\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index ()
    {
        $category = Category::paginate(10);
        $data = ['category' => $category];

        return view('frontend.panel.categories.categories',$data);
    }
    
    public function add (AddCategoryRequest $request)
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

    public function delete ($category_id)
    {   
        $category = Category::find($category_id);
        $category->delete();
        return back()->with('success','دسته بندی با موفقیت حذف شد');
    }

    public function edit ($category_id)
    {    
        $category = Category::find($category_id);

        return view('frontend.panel.categories.edit' , compact('category'));
        
    }
    
    public function update (UpdateCategoryRequest $request , $category_id)
    {   
        $validData = $request->validated();

        $category = Category::find($category_id);

        $result = $category->update([
            'title' => $validData['title'],
            'slug' => $validData['slug']
            
        ]);
        if(!$result ){
            return back()->with('failed','دسته بندی با موفقیت بروزرسانی نشد');

        }

        return back()->with('success','دسته بندی با موفقیت بروزرسانی شد');
    }
    

}
