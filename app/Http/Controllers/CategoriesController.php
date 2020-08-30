<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    protected $data;
    protected $homeURL = 'categories/show';

    private function initDataArr()
    {
        $this->data['items'] = SubCategory::all();
        $this->data['categories'] = Category::all();
        $this->data['title'] = "Products Classifications";
        $this->data['subTitle'] = "Manage Categories and Sub-Categories";
        $this->data['cols'] = ['Category', 'Sub Category', 'Edit'];
        $this->data['atts'] = [ 
            ['foreignUrl' => ['categories/edit', 'SBCT_CATG_ID', 'category', 'CATG_NAME']],
            ['dynamicUrl' => ['products/show/catg/sub/', 'val' => 'id', 'att' => 'SBCT_NAME']], 
            ['edit' => ['url' => 'subcategories/edit/', 'att' => 'id']]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware("auth");
    }

    public function home(){
        $this->initDataArr();
        $this->data['formTitle'] = "Add Sub Category";
        $this->data['formURL'] = "subcategories/insert";
        $this->data['isCancel'] = false;
        $this->data['form2Title'] = "Add Main Category";
        $this->data['form2URL'] = "categories/insert";
        $this->data['isCancel2'] = false;
        return view('products.categories', $this->data);
    }

    public function editSubCategory($id){
        $this->initDataArr();
        $this->data['subcategory'] = SubCategory::findOrFail($id);
        $this->data['formTitle'] = "Manage SubCategory (" . $this->data['subcategory']->SBCT_NAME . ")";
        $this->data['formURL'] = "subcategories/update";
        $this->data['isCancel'] = true;
        $this->data['form2Title'] = "Add Main Categories";
        $this->data['form2URL'] = "categories/insert";
        $this->data['isCancel2'] = false;
        return view('products.categories', $this->data);
    }

    public function editCategory($id){
        $this->initDataArr();
        $this->data['category'] = Category::findOrFail($id);
        $this->data['formTitle'] = "Add Sub-Categories";
        $this->data['formURL'] = "subcategories/insert";
        $this->data['isCancel'] = false;
        $this->data['form2Title'] = "Manage Category (" . $this->data['category']->CATG_NAME . ")";
        $this->data['form2URL'] = "categories/update";
        $this->data['isCancel2'] = true;
        return view('products.categories', $this->data);
    }

    public function insertCategory(Request $request){

        $request->validate([
            "catgName"      => "required|unique:categories,CATG_NAME",
        ]);

        $category = new Category();
        $category->CATG_NAME = $request->catgName;
        $category->CATG_ARBC_NAME = $request->arbcName;
        $category->save();
        return redirect($this->homeURL);
    }
    public function insertSubCategory(Request $request){

        $request->validate([
            "name" => "required|unique:sub_categories,SBCT_NAME",
            "category" => "required"
        ]);

        $subcategory = new SubCategory();
        $subcategory->SBCT_NAME = $request->name;
        $subcategory->SBCT_ARBC_NAME = $request->arbcName;
        $subcategory->SBCT_CATG_ID = $request->category;
        $subcategory->SBCT_DESC = $request->desc;
   
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/catg/', 'public');
            $subcategory->SBCT_IMGE = $path;
        }
        $subcategory->save();
        return redirect($this->homeURL);
    }



    public function updateSubCategory(Request $request){
        $request->validate([
            "id" => "required",
        ]);
        $subcategory = SubCategory::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('sub_categories', "SBCT_NAME")->ignore($subcategory->SBCT_NAME, "SBCT_NAME"),],
            "category" => "required",
            "id" => "required",
        ]);

        $subcategory->SBCT_NAME = $request->name;
        $subcategory->SBCT_ARBC_NAME = $request->arbcName;
        $subcategory->SBCT_CATG_ID = $request->category;
        $subcategory->SBCT_DESC = $request->desc;
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/catg/', 'public');
            $subcategory->SBCT_IMGE = $path;
        }
        $subcategory->save();
        
        return redirect($this->homeURL);
    }


    public function updateCategory(Request $request){
        $request->validate([
            "catgName" => "required",
            "id" => "required",
        ]);

        $category = Category::findOrFail($request->id);
        $category->CATG_NAME = $request->catgName;
        $category->CATG_ARBC_NAME = $request->arbcName;
        $category->save();

        return redirect($this->homeURL);
    }
}
