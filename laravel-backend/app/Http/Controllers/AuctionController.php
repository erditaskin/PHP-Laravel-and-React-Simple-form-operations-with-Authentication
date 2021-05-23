<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\UserSetting;
use App\Repositories\AuctionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * @param Request $request
     * @param AuctionRepository $repository
     * @return JsonResponse
     */
    public function auctionList(Request $request, AuctionRepository $repository): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => $repository->list($request),
        ]);
    }

    /**
     * @param Request $request
     * @param Auction $auction
     * @return JsonResponse
     * @throws \Exception
     */
    public function auctionDetail(Request $request, Auction $auction): JsonResponse
    {
        $auction->load(['bids', 'bids.user', 'autoBid' => function ($query) use ($request) {
            $query->where('user_id', $request->user_id);
        }]);

        if ($auction->close_datetime > new \DateTime()) {
            return response()->json([
                'code' => 404,
                'message' => __('auction.closed'),
                'data' => null,
                'setting' => null,
            ]);
        }

        $setting = UserSetting::query()->where('user_id', $request->user_id)->first();

        return response()->json([
            'code' => 200,
            'data' => $auction,
            'setting' => $setting
        ]);
    }
}
