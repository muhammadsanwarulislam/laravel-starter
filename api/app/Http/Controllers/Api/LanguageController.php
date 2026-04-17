<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct(protected LanguageService $languageService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $languages = $this->languageService->getActiveLanguages();
            return $this->success($languages, 'Languages retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve languages', null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
