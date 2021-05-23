<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AuctionAutoBid
 * @package App\Models
 *
 * @property $auction
 * @property $user
 * @property $setting
 * @property integer $id
 * @property string $user_id
 * @property integer $auction_id
 * @property boolean $status
 */
class AuctionAutoBid extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

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
    public function setting(): BelongsTo
    {
        return $this->belongsTo(UserSetting::class, 'user_id', 'user_id');
    }
}
