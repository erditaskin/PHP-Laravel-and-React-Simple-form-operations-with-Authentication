<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


/**
 * Class BidRepository.
 */
class BidRepository
{
    /**
     * @param $user_ids
     * @return array
     */
    public function lastBidsGroupByUsers($user_ids): array
    {
        return DB::table('auction_bids', 'b1')
            ->selectRaw('b1.user_id, SUM(b1.price) as amount')
            ->leftJoin('auction_bids AS b2', function ($join) {
                $join->on('b1.user_id', '=', 'b2.user_id')
                    ->on('b1.auction_id', '=', 'b2.auction_id')
                    ->on('b1.id', '<', 'b2.id');
            })
            ->whereNull('b2.id')
            ->whereIn('b1.user_id', $user_ids)
            ->groupBy('b1.user_id')
            ->get()
            ->keyBy('user_id')
            ->toArray();
    }

    /**
     * @param $user_ids
     * @return array
     */
    public function lastBids($user_ids): array
    {
        return DB::table('auction_bids', 'b1')
            ->selectRaw('b1.user_id, b1.price')
            ->leftJoin('auction_bids AS b2', function ($join) {
                $join->on('b1.user_id', '=', 'b2.user_id')
                    ->on('b1.auction_id', '=', 'b2.auction_id')
                    ->on('b1.id', '<', 'b2.id');
            })
            ->whereNull('b2.id')
            ->whereIn('b1.user_id', $user_ids)
            ->get()
            ->keyBy('user_id')
            ->toArray();
    }

}
