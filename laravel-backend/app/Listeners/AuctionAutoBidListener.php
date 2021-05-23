<?php

namespace App\Listeners;

use App\Events\AutoBidEvent;
use App\Models\AuctionAutoBid;
use App\Models\AuctionBid;
use App\Repositories\BidRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuctionAutoBidListener
{
    protected $bidRepository;

    /**
     * Create the event listener.
     *
     * @param $bidRepository
     * @return void
     */
    public function __construct(BidRepository $bidRepository)
    {
        $this->bidRepository = $bidRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AutoBidEvent  $event
     * @return void
     */
    public function handle(AutoBidEvent $event)
    {
        $autoBidQuery = $event->auction->autoBids()
            ->with('auction','setting')
            ->orderBy('id', 'ASC');
        $user_ids = $autoBidQuery->get()->pluck('user_id')->toArray();

        $list = $this->bidRepository->lastBidsGroupByUsers($user_ids);
        $lastBids = $this->bidRepository->lastBids($user_ids);

        /** @var AuctionAutoBid $autoBid */
        foreach ($autoBidQuery->get() as $autoBid) {
            $checks = $this->checks($list, $lastBids, $autoBid);
            $amount = $checks->userTotalAmount - $checks->userAuctionLastBid + $event->auction->last_price;
            $maxAmount = optional($autoBid->setting)->max_amount;

            if (
                ($autoBid->user_id === \request()->user_id) || ($maxAmount < 1) ||
                ($checks->userTotalAmount >= $maxAmount) ||
                ($amount >= $maxAmount)
            ) {
                continue;
            }

            $bid = new AuctionBid();
            $bid->auction_id = $autoBid->auction_id;
            $bid->user_id = $autoBid->user_id;
            $bid->price = $event->auction->last_price + 1;
            $bid->save();

            $event->auction->last_price = $bid->price;
            $event->auction->save();
        }
    }

    /**
     * @param array $list
     * @param array $lastBids
     * @param AuctionAutoBid $autoBid
     * @return object
     */
    private function checks(array $list, array $lastBids, AuctionAutoBid $autoBid): object
    {
        return (object) [
            'userTotalAmount' => isset($list[$autoBid->user_id])
                ? optional($list[$autoBid->user_id])->amount
                : 0,
            'userAuctionLastBid' => isset($lastBids[$autoBid->user_id])
                ? optional($lastBids[$autoBid->user_id])->price
                : 0,
        ];
    }
}
