<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function home(){
            $featuredProducts =Product::with('images')->where('is_featured',1)->orderBy('id','asc')->get();
            return view('home',compact('featuredProducts'));

    }

    public function productDetail($slug){
        $product = Product::with('category','images')
            ->where('slug', $slug)
            ->firstOrFail();
           // dd($product);

        return view('product-detail', compact('product'));

    }

    public function about(){
        return view('about');
    }
    public function products(){
    $products =Product::with('images')->where('status',1)->orderBy('id','asc')->get();
       
        return view('product',compact('products'));
    }
}
