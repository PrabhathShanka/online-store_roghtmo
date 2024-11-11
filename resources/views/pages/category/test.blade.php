public function update(Request $request, $id)
{
$request->validate([
'name' => 'required|string|max:255',
'description' => 'required|string',
'price' => 'required|numeric',
'stock' => 'required|integer',
'category_id' => 'required|exists:product_categories,id',
'mainImage' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
'additionalImages' => 'nullable|array|max:5', // Limit to 5 images
'additionalImages.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // Validate each image
]);

$product = Product::findOrFail($id);
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

// Handle additional images
if ($request->hasFile('additionalImages')) {
// Loop through each uploaded file and store them
foreach ($request->file('additionalImages') as $file) {
$imagePath = $file->store('products/', 'public');

// Store each additional image in a related table (assuming you have a product_images table)
$product->images()->create([
'image_path' => $imagePath,
]);
}
}

// If needed, remove old additional images not included in the update (optional)
// This step requires you to identify the images that are to be removed from the database
if ($request->input('removed_images')) {
$removedImageIds = $request->input('removed_images');
foreach ($removedImageIds as $imageId) {
$image = ProductImage::find($imageId);
if ($image && file_exists(storage_path('app/public/' . $image->image_path))) {
unlink(storage_path('app/public/' . $image->image_path));
$image->delete();
}
}
}

session()->flash('success', 'Product updated successfully!');
return redirect()->route('product.index');
}
