<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{

    protected $data;
    protected $homeURL = "products/show/all";
    protected $detailsURL = "products/details/";


    public function __construct()
    {
        $this->middleware('auth');
    }

    ///////////////////Models Pages

    public function home($sub = -1)
    {
        $this->initTableArr(-1, $sub);
        return view("products.table", $this->data);
    }

    public function sale()
    {
        $this->initTableArr(-1, -1, 1);
        return view("products.table", $this->data);
    }

    public function new()
    {
        $this->initTableArr(-1, -1, -1, 1);
        return view("products.table", $this->data);
    }


    public function showCategory(Request $request)
    {
        $request->validate([
            "category" => 'required'
        ]);

        $this->initTableArr($request->category);
        return view("products.table", $this->data);
    }
    public function showSubCategory(Request $request)
    {
        $request->validate([
            "subcategory" => 'required'
        ]);
        $this->initTableArr($request->subcategory);
        return view("products.table", $this->data);
    }

    public function add()
    {
        $this->initAddArr();
        return view("products.add", $this->data);
    }

    public function edit($prodID)
    {
        $this->initAddArr($prodID);
        return view("products.add", $this->data);
    }

    public function filterCategory()
    {
        $this->data['categories'] = Category::all();
        $this->data['subcategories'] = SubCategory::all();

        $this->data['categoryURL'] = 'products/category';
        $this->data['subcategoryURL'] = 'products/subcategory';

        return view('products.filters.categories', $this->data);
    }

    ////////////////////////////////////Profile Functions//////////////////////////////////

    public function details($prodID)
    {
        $product = Product::with("stock", "subcategory")->where("id", $prodID)->get()->first();

        if (isset($product->mainImage->id))
            $this->data['mainImage'] = $product->mainImage->PIMG_URL;
        elseif (isset($product->images[0]))
            $this->data['mainImage'] = $product->images[0]->PIMG_URL;
        else
            $this->data['mainImage'] = asset('images/default_product.jpg');

        $this->data['categories'] = SubCategory::with('category')->get();
        $this->data['raw'] = RawMaterial::all();
        $this->data['formURL'] = "products/update";
        $this->data['formTitle'] = "Edit Model Info";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
        $this->data['addIngredients'] = url('products/ingredients/add/' . $product->id);


        $this->data['items'] = Inventory::with(["product"])->where("INVT_PROD_ID", "=", $prodID)->get();

        $this->data['title'] = "Items Available";
        $this->data['subTitle'] = "View Current Stock for (" . $product->PROD_NAME . ")";
        $this->data['cols'] = ['Production Date', 'Amount'];
        $this->data['atts'] = [
            ['date' => ['att' => 'created_at', 'format' => "Y-M-d"]],
            ['number' => ['att' => 'INVT_AMNT', 'nums' => 2]],
            'INVT_CUNT'
        ];
        //raw materials
        $this->data['rawItems'] = $product->ingredients()->get();
        $this->data['rawTitle'] = "Product Ingrediets";
        $this->data['rawSubTitle'] = "View Ingredients for (" . $product->PROD_NAME . ")";
        $this->data['rawCols'] = ['Raw Material', 'Amount', 'Delete'];
        $this->data['rawAtts'] = [
            ['foreign' => ['raw_material', 'RWMT_NAME']],
            ['number' => ['att' => 'IGDT_GRAM', 'nums' => 0]],
            ['del' => ['url' => 'products/ingredients/delete/', 'att' => 'id']],
        ];

        $this->data['product'] = $product;
        return view("products.details", $this->data);
    }

    public function addIngredients($prodID, Request $request)
    {
        $product = Product::findOrFail($prodID);
        $ingredients = $this->getIngredientsItemsObjectArray($product->id, $request);
        $product->ingredients()->saveMany($ingredients);
        return redirect('products/details/' . $prodID);
    }

    public function deleteIngredient($id){
        $ingredient = Ingredient::findOrFail($id);
        $prodID = $ingredient->IGDT_PROD_ID;
        $ingredient->delete();
        return redirect('products/details/' . $prodID);
    }


    ////////////////////////////////REST Function///////////////////////////
    public function insert(Request $request)
    {
        $request->validate([
            "name" => "required|unique:products,PROD_NAME",
            "desc" => "required",
            "category" => "required|exists:sub_categories,id",
            "wholePrice" => "required|numeric",
            "retailPrice" => "required|numeric",
            "insidePrice" => "required|numeric",
            "cost" => "nullable|numeric",
        ]);

        $product = new Product();

        $product->PROD_NAME = $request->name;
        $product->PROD_ARBC_NAME = $request->arbcName;
        $product->PROD_DESC = $request->desc;
        $product->PROD_ARBC_DESC = $request->arbcDesc;
        $product->PROD_SBCT_ID = $request->category;
        $product->PROD_RETL_PRCE = $request->retailPrice;
        $product->PROD_WHLE_PRCE = $request->wholePrice;
        $product->PROD_INSD_PRCE = $request->insidePrice;
        $product->PROD_COST = $request->cost;


        $product->save();
        return redirect('products/details/' . $product->id);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id"          => "required",
        ]);
        $product = Product::findOrFail($request->id);
        $request->validate([
            "name"          => ["required",  Rule::unique('products', "PROD_NAME")->ignore($product->PROD_NAME, "PROD_NAME"),],
            "desc" => "required",
            "category" => "required|exists:sub_categories,id",
            "wholePrice" => "required|numeric",
            "retailPrice" => "required|numeric",
            "insidePrice" => "required|numeric",
            "cost" => "nullable|numeric",
        ]);

        $product->PROD_NAME = $request->name;
        $product->PROD_ARBC_NAME = $request->arbcName;
        $product->PROD_DESC = $request->desc;
        $product->PROD_ARBC_DESC = $request->arbcDesc;
        $product->PROD_SBCT_ID = $request->category;
        $product->PROD_RETL_PRCE = $request->retailPrice;
        $product->PROD_WHLE_PRCE = $request->wholePrice;
        $product->PROD_INSD_PRCE = $request->insidePrice;
        $product->PROD_COST = $request->cost;


        $product->save();
        return redirect('products/details/' . $product->id);
    }


    //////////////////Initializing Data Arrays
    private function initTableArr($category = -1, $subcategory = -1, $sale = -1, $newArrivals = -1)
    {
        if ($category != -1) {

            $category = Category::findOrFail($category);
            $this->data['items'] = $category->products;
            $this->data['title'] = $category->CATG_NAME . "'s Models";
            $this->data['subTitle'] = "Showing all Models for " . $category->CATG_NAME;
        } elseif ($subcategory != -1) {

            $this->data['items'] = Product::where("PROD_SBCT_ID", '=', $subcategory);
            $subcategory = SubCategory::findOrFail($subcategory);
            $this->data['title'] = $subcategory->SBCT_NAME . "'s Models";
            $this->data['subTitle'] = "Showing all Models for " . $subcategory->SBCT_NAME;
        } else {
            $this->data['items'] = Product::withCount('stock')->get();
            $this->data['title'] = "All Models";
            $this->data['subTitle'] = "Showing all Models";
        }
        $this->data['cols'] = ['Model Title', 'Arabic Title', "in Stock", 'Retail Price', 'Whole Price', 'Inside Price', 'Cost', 'Offer', 'Edit'];
        $this->data['atts'] = [

            ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_NAME"]],
            ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_ARBC_NAME"]],
            ['sumForeign' => ['rel' => "stock", "att" => "INVT_KMS"]],
            ['number' => ['att' => 'PROD_RETL_PRCE', 'nums' => 2],],
            ['number' => ['att' => 'PROD_WHLE_PRCE', 'nums' => 2],],
            ['number' => ['att' => 'PROD_INSD_PRCE', 'nums' => 2],],
            'PROD_COST',
            'PROD_OFFR',
            ['edit' => ['url' => 'products/edit/', 'att' => 'id']],
        ];
        // dd($this->data['items'][0]->stock_count);
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($prodID = -1)
    {
        if ($prodID != -1) {
            $this->data['product'] = Product::findOrFail($prodID);
            $this->data['formURL'] = "products/update";
        } else {
            $this->data['formURL'] = "products/insert/";
        }
        $this->data['categories'] = SubCategory::with('category')->get();
        $this->data['formTitle'] = "Add New Model";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    private function getIngredientsItemsObjectArray($prodID, Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push($retArr, new Ingredient(
                ["IGDT_RWMT_ID" => $item, "IGDT_GRAM" => $request->grams[$index]]
            ));
        }
  
        return $retArr;
    }
}
