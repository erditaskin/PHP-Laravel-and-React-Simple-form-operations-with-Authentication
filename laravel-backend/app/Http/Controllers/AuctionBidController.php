<?php

namespace App\Http\Controllers;

use App\Events\AutoBidEvent;
use App\Http\Requests\AuctionBidRequest;
use App\Models\Auction;
use App\Models\AuctionBid;

class AuctionBidController extends Controller
{
    /**
     * @param AuctionBidRequest $request
     * @param Auction $auction
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function applyBid(AuctionBidRequest $request, Auction $auction): \Illuminate\Http\JsonResponse
    {
        $auction->load(['bids', 'bids.user']);

        if ($auction->close_datetime > new \DateTime()) {
            $auction->refresh();

            return response()->json([
                'code' => 400,
                'message' => __('auction.closed'),
                'data' => $auction,
            ]);
        }

        if ($auction->last_price >= $request->get('price') || $auction->price >= $request->get('price')) {
            $auction->refresh();

            return response()->json([
                'code' => 406,
                'message' => __('auction.bid.error.lower'),
                'data' => $auction,
            ]);
        }

        $lastBid = $auction->bids()->latest();
        if ($lastBid->first() && optional($lastBid->first())->user_id === $request->user_id) {
            return response()->json([
                'code' => 406,
                'message' =>  __('auction.bid.error.highest'),
                'data' => $auction,
            ]);
        }

        $auction->bids()->create([
            'price' => $request->get('price'),
            'user_id' => $request->user_id,
        ]);


        $auction->last_price = $request->get('price');
        $auction->save();

        AutoBidEvent::dispatch($auction);

        $auction->refresh();
        $auction->load(['bids', 'bids.user']);

        return response()->json([
            'code' => 200,
            'data' => $auction,
        ]);
    }
}
