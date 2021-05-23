<?php

namespace App\Models;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * Class Auction
 * @package App\Models
 *
 * @property $bids
 * @property $user
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $price
 * @property integer $last_price
 * @property \DateTime $close_datetime
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Auction extends Model
{
    use HasFactory;

    protected $appends = ['full_image'];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOpen($query)
    {
        return $query->where('close_datetime', '>', new \DateTime());
    }

    /**
     * @param $query
     * @param $searchTerm
     * @return mixed
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%");
    }

    /**
     * @return Application|UrlGenerator|string
     */
    public function getFullImageAttribute()
    {
        return url("uploads/items/{$this->image}");
    }

    /**
     * @return HasMany
     */
    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class)->orderBy('id','DESC');
    }

    /**
     * @return HasMany
     */
    public function autoBids(): HasMany
    {
        return $this->hasMany(AuctionAutoBid::class);
    }

    /**
     * @return HasOne
     */
    public function autoBid(): HasOne
    {
        return $this->hasOne(AuctionAutoBid::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
