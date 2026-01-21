<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LocalizationService;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    protected $localizationService;

    public function __construct(LocalizationService  $localizationService)
    {
        $this->localizationService = $localizationService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('X-Locale')) {
            $locale = $request->header('X-Locale');
            $this->localizationService->setLocale($locale);
        }

        $response = $next($request);

        return $response;
    }
}
