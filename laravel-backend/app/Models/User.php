<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models
 *
 * @property $settings
 * @property $bids
 * @property $autoBids
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class User extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * @return HasMany
     */
    public function autoBids(): HasMany
    {
        return $this->hasMany(AuctionAutoBid::class);
    }

    /**
     * @return HasMany
     */
    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }


}
