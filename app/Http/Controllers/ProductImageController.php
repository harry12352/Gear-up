<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductImageController extends Controller
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(['file' => 'required', 'product_id' => 'required']);
        $path = FileStorage::store($request['file'], 'images');
        $file = File::create(['path' => $path, 'resource_id' => $request['product_id'], 'resource_name' => 'product']);
        $image_remove_path = route('images.delete', ['file' => $file->id]);
        return response()->json(['error' => false, 'message' => 'Image Created successfully', 'image_remove_path' => $image_remove_path], 200);

    }

    public function delete(File $file, FileStorage $storage)
    {
        if ($file['resource_name'] === 'product') {
            $product = Product::find($file['resource_id']);
            if (Auth::id() == $product['user_id']) {
                if ($storage->delete($file->path)) {
                    $file->delete();
                    return response()->json(['error' => 'false', 'message' => 'Image deleted successfully'], 200);
                } else {
                    return response()->json(['error' => 'True', 'message' => 'Image deletion failed'], 500);
                }
            }
        }
        return response()->json(['error' => 'True', 'message' => 'You can not perform this action'], 403);
    }
}

