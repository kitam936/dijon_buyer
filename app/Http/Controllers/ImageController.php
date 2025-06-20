<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImageController extends Controller
{
    public function index()
    {
        $files = Storage::files('public/sku_images');
        $images = array_map(function ($path) {
            return basename($path);
        }, $files);

        return view('images.image_DL_index', compact('images'));
    }

    public function download(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'string',
        ]);

        $zip = new ZipArchive;
        $zipFileName = 'sku_images_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($request->images as $image) {
                $filePath = storage_path('app/public/sku_images/' . $image);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $image);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
