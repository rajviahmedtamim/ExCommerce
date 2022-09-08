<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
//        $category=Category::all();
        $category=DB::table('categories')->get();
        $bannerproduct=Products::where('product_slider',1)->latest()->first();
//        $bannerproduct=DB::table('products')->where('product_slider',1)->latest()->first();
        return view('frontend.index',compact('category','bannerproduct'));
    }

//    single product page
    public function productDetails($slug){
        $product=Products::where('slug',$slug)->first();
        return view('frontend.product_details',compact('product'));
    }
}
