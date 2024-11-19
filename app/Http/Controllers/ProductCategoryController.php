<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{



    public function index(Request $request)
    {
        // Check if a sort column is provided; default to 'name' for alphabetical order or 'id' if 'name' is not preferred.
        $defaultSortColumn = 'name'; // Change to 'id' if you want to use 'id' as the default sort column
        $sortColumn = $request->input('sort_column', $defaultSortColumn);
        $sortDirection = $request->input('sort_direction', 'asc'); // Default to ascending order

        // Start the query with optional search functionality
        $query = ProductCategory::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Apply sorting to the query
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results and append parameters to pagination links
        $categories = $query->paginate(5)->appends([
            'sort_column' => $sortColumn,
            'sort_direction' => $sortDirection,
            'search' => $request->input('search')
        ]);

        return view('pages.category.index', compact('categories', 'sortColumn', 'sortDirection'));
    }



    public function search(Request $request)
    {
        $search = $request->input('search');

        if (empty($search)) {
            return redirect()->route('category.index');
        }

        $category = ProductCategory::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->paginate(2);

        return view('pages.category.index', compact('category', 'search'));
    }

    public function create()
    {
        return view('pages.category.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|unique:product_categories,name',
        ]);

        // Create the category if validation passes
        ProductCategory::create([
            'name' => $validatedData['name'],
        ]);

        // Set a success message for category creation
        session()->flash('success', 'Category created successfully!');

        return redirect()->route('category.index');
    }


    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:product_categories,name,' . $id,
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        session()->flash('success', 'Category updated successfully!');
        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        session()->flash('success', 'Category deleted successfully!');
        return redirect()->route('category.index');
    }
}
