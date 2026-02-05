<?php

namespace App\Http\Controllers\Admin;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;

class TempImageController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;
        if (!empty($image)) {

            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;
            $tempImage = new TempImage();
            $tempImage->image = $newName;
            $tempImage->save();
            $image->move(public_path() . '/temp', $newName);

            //generate thumbnail

            $sourcePath = public_path() . '/temp/' . $newName; //original image path
            $destPath = public_path() . '/temp/thumb/' . $newName; //thumbnail path



            $manager = new ImageManager(new Driver()); 

            // Read image from file system
            $image = $manager->read($sourcePath);

            // Resize/crop like fit(300, 275)
            $image = $image->cover(300, 275);

            // Save the thumbnail
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'imagePath'=>asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Image not uploaded',
        ]);}
}

