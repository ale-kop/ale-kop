<?php

namespace App\Http\Controllers;

use App\Models\ContentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentImageController extends Controller
{
    /**
     * Upload an image to be embedded in rich text content (posts, etc).
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:10240'],
        ]);

        $contentImage = ContentImage::create(['user_id' => Auth::id()]);
        $contentImage->addMediaFromRequest('image')->toMediaCollection('content-image');

        return response()->json([
            'url' => $contentImage->url('medium'),
            'largeSrc' => $contentImage->url('large'),
        ]);
    }
}
