<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        $file = $request->file('image');

        $name = time() . '_' . Str::random(10) . '.webp';
        $dir  = public_path('temp/products');
        $path = $dir . '/' . $name;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        Image::make($file)
            ->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 90)
            ->save($path);

        return response()->json([
            'name' => $name
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


        $categories = Category::all();

        return view('admin.product.create', compact('categories'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1', // ❌ 0 not allowed
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? 0,
                'status' => $request->status,
            ]);

            if ($request->images) {
                $images = explode(',', $request->images);

                foreach ($images as $img) {

                    $from = public_path('temp/products/' . $img);
                    $to   = public_path('uploads/products/' . $img);

                    if (file_exists($from)) {
                        rename($from, $to);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $img
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('product.index')
            ->with('success', 'Product saved successfully');
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
            'title'       => 'required|string|max:255',
            'slug'        => 'required|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:1', // ❌ 0 not allowed
            'status'      => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $id) {

            $product = Product::findOrFail($id);

            // ✅ Update product
            $product->update([
                'title'             => $request->title,
                'slug'              => $request->slug,
                'category_id'       => $request->category_id,
                'price'             => $request->price,
                'short_description' => $request->short_description,
                'description'       => $request->description,
                'is_featured'       => $request->is_featured ?? 0,
                'status'            => $request->status,
            ]);

            // ✅ New images (temp → uploads)
            if ($request->images) {
                $images = explode(',', $request->images);

                foreach ($images as $img) {

                    $from = public_path('temp/products/' . $img);
                    $to   = public_path('uploads/products/' . $img);

                    if (file_exists($from)) {
                        rename($from, $to);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image'      => $img,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('product.index')
            ->with('success', 'Product updated successfully');
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
