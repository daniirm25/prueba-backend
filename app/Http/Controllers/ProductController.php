<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    
    public function getProducts(Request $request)
    {
        try {
            $search = isset($request->search) ? $request->search : null;
            $products = Product::search($search)->get();
            if(!$products->isEmpty()){
                $filter = 0;
                if($search != null){
                    $filter = 1;
                }
                return view('components/products', compact('products', 'filter'));
            }
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
        }
    }


}
