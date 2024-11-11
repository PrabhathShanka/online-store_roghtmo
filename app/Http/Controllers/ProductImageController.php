<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{


    public function index($id)
    {
        $images = Product_Image::where('product_id', $id)->paginate(1);


        return view('pages.images.index', compact('images', 'id'));
    }


    public function create($id)
    {
        return view('pages.images.create', compact('id'));
    }

    // Store the uploaded image
    public function store(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Store the image
        $path = $request->file('image')->store('product_images', 'public');

        // Save image data to the database
        Product_Image::create([
            'image_path' => $path,
            'product_id' => $id,
        ]);

        return redirect()->route('product.images', $id)->with('success', 'Image uploaded successfully.');
    }

    public function destroy($id, $imageId)
    {
        // Find the image by ID
        $image = Product_Image::findOrFail($imageId);

        // Delete the image file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the image from the database
        $image->delete();

        return redirect()->route('product.images', $id)->with('success', 'Image deleted successfully.');
    }
}
