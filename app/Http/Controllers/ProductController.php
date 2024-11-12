<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Image;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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

        return view('pages.product.index', compact('products', 'categories', 'sortColumn', 'sortDirection'));
    }





    public function create()
    {
        $category = ProductCategory::all();
        return view('pages.product.create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:product_categories,id',
            'mainImage' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'additionalImages' => 'array|max:5',
            'additionalImages.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);

        // Handle main image upload
        if ($request->hasFile('mainImage')) {
            $imagePath = $request->file('mainImage')->store('products/MainImage', 'public');
            $data['mainImage'] = $imagePath;
        }

        // Create the product
        $product = Product::create($data);

        // Handle additional images
        if ($request->hasFile('additionalImages')) {
            foreach ($request->file('additionalImages') as $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create(['image_path' => $imagePath]);
            }
        }

        session()->flash('success', 'Product added successfully with images!');
        return redirect()->route('product.index');
    }







    public function edit($id)
    {
        // Retrieve the product along with its related images and categories
        $product = Product::with('images')->findOrFail($id);
        $category = ProductCategory::all(); // You can keep this line for categories

        // Return the edit view with product, categories, and images
        return view('pages.product.edit', compact('product', 'category'));
    }





    public function update(Request $request, $id)
    {
        // Retrieve the product and count existing additional images
        $product = Product::findOrFail($id);
        $existingImageCount = $product->images()->count();

        // Calculate how many additional images can be uploaded (max 5 total)
        $remainingSlots = 5 - $existingImageCount;

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:product_categories,id',
            'mainImage' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'additionalImages' => ['nullable', 'array', 'max:' . $remainingSlots],
            'additionalImages.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // Prepare data for updating the product
        $data = $request->all();

        // Handle main image upload
        if ($request->hasFile('mainImage')) {
            $defaultImagePath = 'products/MainImage/default.png';

            // Delete the old image if it exists and is not the default image
            if ($product->mainImage && $product->mainImage !== $defaultImagePath) {
                $imagePath = storage_path('app/public/' . $product->mainImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Store the new image
            $imagePath = $request->file('mainImage')->store('products/MainImage', 'public');
            $data['mainImage'] = $imagePath;
        }

        // Update product details (name, description, price, etc.)
        $product->update($data);

        // Handle additional images if provided
        if ($request->hasFile('additionalImages')) {
            foreach ($request->file('additionalImages') as $file) {
                $imagePath = $file->store('products/', 'public');

                // Store each additional image in a related table (assuming you have a product_images table)
                $product->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }

        // Set success message and redirect
        session()->flash('success', 'Product updated successfully!');
        return redirect()->route('product.index');
    }






    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete main image if it is not the default image
        $imagePath = 'storage/' . $product->mainImage;
        $defaultImagePath = 'products/MainImage/default.png';

        if ($product->mainImage && $product->mainImage !== $defaultImagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete additional images associated with this product
        foreach ($product->images as $additionalImage) {
            $additionalImagePath = 'storage/' . $additionalImage->image_path;
            if (file_exists($additionalImagePath)) {
                unlink($additionalImagePath); // Delete each additional image file
            }
            $additionalImage->delete(); // Delete record from the database
        }

        // Finally, delete the product itself
        $product->delete();

        session()->flash('success', 'Product and associated images deleted successfully!');
        return redirect()->route('product.index');
    }




    public function deleteImage($imageId)
    {
        $image = Product_Image::find($imageId);

        if ($image) {
            // Get the image file path
            $imagePath = storage_path('app/public/' . $image->image_path);

            // Check if the file exists and delete it
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }

            // Delete the image record from the database
            $image->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
