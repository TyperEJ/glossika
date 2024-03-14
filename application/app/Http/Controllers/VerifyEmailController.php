<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyEmail\VerifyByCodeRequest;
use App\Http\Requests\VerifyEmail\VerifyByLinkRequest;
use App\Services\VerifyEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    private VerifyEmailService $verifyEmailService;

    public function __construct(VerifyEmailService $verifyEmailService)
    {
        $this->verifyEmailService = $verifyEmailService;
    }

    public function sendVerifyEmailByLink(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email has been verified',
            ], 400);
        }

        $request->user()->sendEmailVerificationByLink();

        return response()->json([
            'message' => 'Email verification send',
        ]);
    }

    public function verifyByLink(VerifyByLinkRequest $request): JsonResponse
    {
        $isVerify = $this->verifyEmailService->verifyByLink($request->input('token'));

        if (! $isVerify) {
            return response()->json([
                'message' => 'Verification failed',
            ], 400);
        }

        return response()->json([
            'message' => 'Verification success',
        ]);
    }

    public function sendVerifyEmailByCode(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email has been verified',
            ], 400);
        }

        $request->user()->sendEmailVerificationByCode();

        return response()->json([
            'message' => 'Email verification send',
        ]);
    }

    public function verifyByCode(VerifyByCodeRequest $request): JsonResponse
    {
        $isVerify = $this->verifyEmailService->verifyByCode(
            $request->user(),
            $request->input('code')
        );

        if (! $isVerify) {
            return response()->json([
                'message' => 'Verification failed',
            ], 400);
        }

        return response()->json([
            'message' => 'Verification success',
        ]);
    }
}
