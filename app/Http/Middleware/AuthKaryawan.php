<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AuthKaryawan
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('karyawan_id')) {
            return redirect()->route('login.form')->withErrors([
                'login_error' => 'Silahkan login terlebih dahulu.'
            ]);
        }

        $response = $next($request);

        // ✅ JANGAN sentuh response download (Excel, PDF, dll)
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        // ✅ Response normal (HTML / JSON)
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }
}
