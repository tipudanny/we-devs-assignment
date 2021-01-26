<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $page = ( request()->get( 'page' ) ) ? request()->get( 'page' ) : 1;
        $products = Product::paginate( 10 );
        return ProductResource::collection($products);
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->all();
        try {
            $data['image'] = time().$request->image->getClientOriginalName();
            $request->image->storeAs('/public', $data['image']);
            $product = Product::create($data);
            return response()->json( [ 'product' => $product, 'message' => 'Product Added Successfully' ], 201 );
        } catch ( Exception $e ) {
            return response()->json(['error' => 'Failed to add product!'], 422);
        }
        //return $data;
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product){
            return new ProductDetailsResource($product);
        }
        return response()->json(['error' => 'No product found.'], 422);
    }

    public function update(ProductUpdateRequest $request,$id)
    {
        $data       =  $request->all();
        $product    = Product::findOrFail($id);

        $product->title         =  $data['title'];
        $product->description   =  $data['description'];
        $product->price         =  $data['price'];
        $product->image         =  $product['image'];

        if ($data['image'] !='' && $request->hasFile('image')){
            $name = time().$request->image->getClientOriginalName();
            unlink(public_path().'/storage/'.$product->image);
            $product->image     =  $name;
            $request->image->storeAs('/public', $name);
        }
        try {
            $product->save();
            return response()->json( [ 'product' => $product, 'message' => 'Product Update Successfully' ], 201 );
        } catch ( Exception $e ) {
            return response()->json(['error' => 'Failed to update product!'], 422);
        }

    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if (Storage::disk('public')->exists($product->image)) {
                Product::destroy($id);
                unlink(public_path().'/storage/'.$product->image);
            }
            return response()->json( [ 'message' => 'Product delete successfully' ], 201 );
        } catch ( Exception $e ) {
            return response()->json(['error' => 'Failed to delete product!'], 422);
        }
    }


}
