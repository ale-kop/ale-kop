<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DownloadController extends Controller
{
    public function show(Request $request)
    {
        $url = $request->query('url');

        abort_unless($url && filter_var($url, FILTER_VALIDATE_URL), 400);

        return view('download-temporary', compact('url'));
    }

    public function stream(Request $request)
    {
        $url = $request->query('url');

        abort_unless($url && filter_var($url, FILTER_VALIDATE_URL), 400);

        $parsed = parse_url($url);
        abort_unless(
            isset($parsed['host']) && str_ends_with($parsed['host'], 'alekop.com'),
            403
        );

        $response = Http::timeout(30)->get($url);

        abort_unless($response->successful(), 404);

        $filename = basename(urldecode($parsed['path'] ?? 'arquivo'));
        $contentType = $response->header('Content-Type') ?? 'application/octet-stream';

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, $filename, [
            'Content-Type' => $contentType,
        ]);
    }
}
