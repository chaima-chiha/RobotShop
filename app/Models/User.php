<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens ,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'adresse',
        'telephone',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function orders()
    {
    return $this->hasMany(Order::class);
    }
    //public function cart()
    //{  return $this->hasOne(Cart::class)->withDefault();}

    public function cart()
    {
        return $this->hasOne(Cart::class)->withDefault(function ($cart) {
            $cart->save(); // Sauvegarde automatique du panier s'il n'existe pas
        });
    }
    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, Cart::class);
    }

public function videoViews()
{
    return $this->hasMany(VideoView::class);
}
public function videoActivationCodes()
{
    return $this->hasMany(VideoActivationCode::class);
}

}

