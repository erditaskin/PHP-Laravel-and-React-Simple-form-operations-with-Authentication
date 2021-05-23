<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AuctionBid
 * @package App\Models
 *
 * @property $auction
 * @property $user
 * @property integer $id
 * @property integer $user_id
 * @property integer $auction_id
 * @property integer $price
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class AuctionBid extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'user_id'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

}
