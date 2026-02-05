<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
class CategoryController extends Controller
{

   public static function middleware(): array
    {
        return [
            new Middleware('permission:create category', only: ['create']),
            new Middleware('permission:view category', only: ['index']),
            new Middleware('permission:	edit category', only: ['edit']),
            new Middleware('permission:delete category', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $categories = Category::orderBy('id', 'asc')->paginate(10);
        $data['categories'] = $categories;

        return view('admin.category.index', $data);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|string|max:255',
            'slug'   => 'nullable|string|max:255|unique:categories,slug',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('category.create')->withErrors($validator)->withInput();
        }
        $category = new Category;
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $data['category'] = $category;
        return view('admin.category.edit', $data);
    }

    public function update($id, Request $request)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('error', 'Category not found');
        }
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();
        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
{
    $category = Category::find($id);
    if (!$category) {
        return response()->json([
            'status' => false,
            'error' => 'Category not found',
        ]);
    }

    $category->delete();

    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully',
    ]);
}

}
