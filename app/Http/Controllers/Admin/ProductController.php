<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:create products', only: ['create']),
            new Middleware('permission:view products', only: ['index']),
            new Middleware('permission:edit products', only: ['edit']),
            new Middleware('permission:delete products', only: ['destroy']),
        ];
    }

    public function deleteImage($id)
    {
        $image = ProductImage::find($id);

        if (!$image) {
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ], 404);
        }

        $filePath = public_path('uploads/products/' . $image->image);

        if (file_exists($filePath)) {
            unlink($filePath);
        }


        $image->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_id' => 'required'
        ]);

        $image = $request->file('image');
        $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('uploads/products'), $imageName);

        $productImage = ProductImage::create([
            'product_id' => $request->product_id,
            'image' => $imageName
        ]);

        return response()->json([
            'id' => $productImage->id,
            'imagePath' => asset('uploads/products/' . $imageName)
        ]);
    }
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'products_' . now()->format('Y_m_d_His') . '.csv';

        // Use array syntax to avoid Intelephense warning
        $products = Product::select([
            'id',
            'title',
            'slug',
            'price',
            'status',
            'created_at',
        ])
            ->orderBy('id', 'asc')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($products) {
            // UTF-8 BOM for Excel
            echo "\xEF\xBB\xBF";

            $file = fopen('php://output', 'w');

            // CSV Header Row
            fputcsv($file, [
                'ID',
                'Title',
                'Slug',
                'Price',
                'Status',
                'Created At',
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->title,
                    $product->slug,
                    $product->price,
                    $product->status ? 'Active' : 'Inactive',
                    // Force text to avoid ######## in Excel
                    "'" . $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'asc')->with('images', 'category')->paginate(10);

        $data['products'] = $products;
        return view('admin.product.index', $data);
    }

    public function create()
    {
        $product = Product::create([
            'title' => 'temp',
            'slug' => 'temp-' . uniqid(),
            'price'=>0,
            'status' => 0
        ]);

        $categories = Category::all();

        return view('admin.product.create', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:1',

        ]);
        $product = Product::findOrFail($request->product_id);
        if ($validator->passes()) {
            $product->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? 0,
                'status' => $request->status
            ]);
        }


        return redirect()->route('product.index')->with('success', 'Product saved');
    }



    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            // 'category_id' => 'required',
            'price' => 'required',
            'status' => 'required'
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status
        ]);

        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => false]);
        }

        $product->delete();

        session()->flash('success', 'Product deleted successfully!');

        return response()->json(['status' => true]);
    }
}
