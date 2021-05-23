<?php

namespace App\Repositories;

use App\Models\Auction;

/**
 * Class AuctionRepository.
 */
class AuctionRepository
{
    /**
     * @param $request
     * @return mixed
     */
    public function list($request)
    {
        return Auction::open()
            ->when($request->has('searchTerm'), function ($query) use ($request) {
                return $query->search($request->get('searchTerm'));
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                return $query->orderBy('last_price', $request->get('sort') === 'asc' ? 'asc' : 'desc');
            })
            ->when(!$request->has('sort'), function ($query) use ($request) {
                return $query->orderBy('id', 'DESC');
            })
            ->paginate(8);
    }
}
