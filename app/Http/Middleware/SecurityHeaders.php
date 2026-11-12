<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add standard security headers to every response.
     * These headers protect against clickjacking, MIME-sniffing,
     * XSS attacks, and information leakage.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking — only allow framing from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Legacy XSS protection for older browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control how much referrer info is sent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict browser features/APIs — allow geolocation only from self (for absensi)
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(self)');

        // Prevent pages from being used in a cache after sensitive actions
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        return $response;
    }
}
