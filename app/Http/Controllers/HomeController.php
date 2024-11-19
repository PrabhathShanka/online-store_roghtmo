<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_Image;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{


    public function getImages($id)
    {
        $images = Product_Image::where('product_id', $id)->get(['image_path']);
        $images = $images->map(function ($image) {
            $image->image_path = asset('storage/' . $image->image_path);
            return $image;
        });

        return response()->json(['images' => $images]);
    }



    public function index(Request $request)
    {

        // Set default values for sorting
        $sortColumn = $request->input('sort_column', 'id'); // Default to 'id' for sorting by ID
        $sortDirection = $request->input('sort_direction', 'asc'); // Default to ascending order

        // Start the query, loading related images and category
        $query = Product::with(['images', 'category']);

        // Category filter if category_id is provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search filter if search is provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply     to the query
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results and append parameters to pagination links
        $products = $query->paginate(5)->appends($request->only(['sort_column', 'sort_direction', 'search', 'category_id']));

        // Get all categories to display in the filter
        $categories = ProductCategory::all();

        // return view('pages.product.index', compact('products', 'categories', 'sortColumn', 'sortDirection'));

        return view('pages.home.index', compact('products', 'categories', 'sortColumn', 'sortDirection'));
    }
}
