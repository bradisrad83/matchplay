<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\Team;

Route::get('/', function() {
    return redirect()->to('/league/league-home');
});
Route::get('/brad-test', function() {
    return Team::latest()->first();
});
if (app()->environment('local')) {
    Route::get('/_health/url', function (Request $request) {
        $appUrl = config('app.url');
        $appScheme = parse_url($appUrl ?? '', PHP_URL_SCHEME) ?: null;

        $requestUrl = $request->fullUrl();
        $requestScheme = $request->getScheme(); // 'http' or 'https'

        // Base URL the public disk will generate, e.g. https://matchplay.test/storage
        $storageBase = rtrim(Storage::disk('public')->url(''), '/');
        $storageScheme = parse_url($storageBase ?: '', PHP_URL_SCHEME) ?: null;

        $symlinkOk = is_link(public_path('storage')) || is_dir(public_path('storage'));

        $issues = [];

        if ($appScheme && $requestScheme !== $appScheme) {
            $issues[] = "APP_URL scheme ($appScheme) doesn't match current request scheme ($requestScheme).";
        }

        if ($storageScheme && $requestScheme !== $storageScheme) {
            $issues[] = "Storage public URL scheme ($storageScheme) doesn't match current request scheme ($requestScheme).";
        }

        if (! $symlinkOk) {
            $issues[] = 'public/storage symlink missing or unreadable. Run: php artisan storage:link';
        }

        return response()->json([
            'ok' => empty($issues),
            'request_url' => $requestUrl,
            'request_scheme' => $requestScheme,
            'app_url' => $appUrl,
            'app_scheme' => $appScheme,
            'storage_public_base_url' => $storageBase,
            'storage_public_scheme' => $storageScheme,
            'public_storage_symlink_ok' => $symlinkOk,
            'issues' => $issues,
            'tips' => [
                'If schemes mismatch' => 'Set APP_URL to the same scheme as the request (http vs https), then run: php artisan optimize:clear',
                'If storage blocked' => 'Ensure uploads use disk=public & visibility=public, and that public/storage is linked.',
            ],
        ], empty($issues) ? 200 : 500);
    })->name('url.health');
}
