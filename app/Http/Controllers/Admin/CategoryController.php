<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\categories\AddCategoryRequest;
use App\Http\Requests\admin\categories\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    private $keyForLang = ['key' => ' دسته بندی '] ;

    public function index ()
    {
        $category = Category::paginate(10);
        return view('frontend.panel.categories.categories',compact('category'));
    }

    public function add_form ()
    {
        return view('frontend.panel.categories.add-categories');
    }
    
    public function added (AddCategoryRequest $request)
    {
        $validData = $request->validated();

        $result = Category::create([
            'title' => $validData['title'],
            'slug'  => $validData['slug']
        ]);

        if (!$result) 
        {
            return back()->with('failed' , __('conditions.failed_add' , $this->keyForLang ));
        }
          
        return back()->with('success' , __('conditions.success_add' , $this->keyForLang));
    }

    public function delete ($category_id)
    {   
        Category::findOrFail($category_id)->delete();
        return back()->with('success',__('conditions.products.success_delete' , $this->keyForLang));
    }

    public function edit ($category_id)
    {    
        $category = Category::find($category_id);

        return view('frontend.panel.categories.edit' , compact('category'));
        
    }
    
    public function update (UpdateCategoryRequest $request , $category_id)
    {   
        $validData = $request->validated();

        $category = Category::findOrFail($category_id);

        $result = $category->update([
            'title' => $validData['title'],
            'slug' => $validData['slug']
            
        ]);
        if(!$result ){
            return back()->with('failed', __('conditions.failed_update' , $this->keyForLang)) ;

        }

        return back()->with('success' , __('conditions.success_update' , $this->keyForLang) );
    }
    

}
