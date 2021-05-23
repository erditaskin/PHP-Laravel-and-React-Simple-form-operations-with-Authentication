<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionAutoBidRequest;
use App\Models\Auction;
use App\Models\AuctionAutoBid;
use Illuminate\Http\JsonResponse;

class AuctionAutoBidController extends Controller
{
    /**
     * @param AuctionAutoBidRequest $request
     * @param Auction $auction
     * @return JsonResponse
     */
    public function switchAutoBidMode(AuctionAutoBidRequest $request, Auction $auction): JsonResponse
    {
        /** @var AuctionAutoBid $checkAutoBid */
        $checkAutoBid = AuctionAutoBid::query()
            ->where('user_id', $request->user_id)
            ->where('auction_id', $auction->id)
            ->first();

        if ($request->get('status') === true) {
            if (!$checkAutoBid) {
                $autoBid = new AuctionAutoBid();
                $autoBid->auction_id = $auction->id;
                $autoBid->user_id = $request->user_id;
                $autoBid->save();
            }

            return response()->json([
                'code' => 200,
                'message' => __('auction.autoBid.activated'),
                'data' => null,
            ]);
        } elseif ($checkAutoBid) {
            $checkAutoBid->delete();
        }

        return response()->json([
            'code' => 200,
            'message' =>  __('auction.autoBid.deactivated'),
            'data' => null,
        ]);
    }
}
