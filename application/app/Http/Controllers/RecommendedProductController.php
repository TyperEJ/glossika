<?php

namespace App\Http\Controllers;

use App\Services\RecommendedProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendedProductController extends Controller
{
    private RecommendedProductService $recommendedProductService;

    public function __construct(RecommendedProductService $recommendedProductService)
    {
        $this->recommendedProductService = $recommendedProductService;
    }

    public function index(Request $request): JsonResponse
    {
        $productList = $this->recommendedProductService->getList();

        return response()->json([
            'message' => 'Success',
            'data' => $productList,
        ]);
    }
}
