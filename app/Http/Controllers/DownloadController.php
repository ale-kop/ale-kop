<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DownloadController extends Controller
{
    private function validateUrl(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $parsed = parse_url($url);

        return isset($parsed['scheme'], $parsed['host'])
            && in_array($parsed['scheme'], ['http', 'https'])
            && str_ends_with($parsed['host'], 'alekop.com');
    }

    public function show(Request $request)
    {
        $url = $request->query('url');

        abort_unless($this->validateUrl($url), 400);

        // Build stream URL manually to avoid double-encoding
        $streamUrl = url('/baixar/arquivo') . '?url=' . rawurlencode($url);

        return view('download-temporary', compact('url', 'streamUrl'));
    }

    public function stream(Request $request)
    {
        $url = $request->query('url');

        abort_unless($this->validateUrl($url), 403);

        $parsed = parse_url($url);
        $response = Http::timeout(60)->get($url);

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
