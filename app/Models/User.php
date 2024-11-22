<?php

namespace App\Models;

// use Illuminate\Contracts\auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims(array $extraClaims = []): array
    {
        return [];
    }
    protected $fillable = [
        'name',
        'email',
        'tel',
        'password',
        'verification_code',
        'is_verified',
        'surname',
        'surname_2',
        'nickname',
        'nickname_true',
        'site',
        'instagram',
        'whatsapp',
        'categories_id',
        'cities_id',
        'cost_from',
        'cost_up',
        'details',
        'about_yourself',
        'language',
        'photos',
        'gallery',
        'role',
        'email_token',
        'isActive',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function statistic()
    {
        return $this->hasMany(Statistic::class, 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id');
    }
    public function scopeWithCategoriesAndCity($query, array $categories, $cityId)
    {
        $query->where('cities_id', $cityId);

        $query->where(function ($query) use ($categories) {
            foreach ($categories as $category) {
                $query->orWhere('categories_id', 'LIKE', '%"' . $category . '"%');
            }
        });

        return $query;
    }
    public function getCategories()
    {
        $categoryIds = json_decode($this->categories_id, true);

        if (!is_array($categoryIds)) {
            return collect();
        }

        return Category::whereIn('id', $categoryIds)->get();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function receivedComments()
    {
        return $this->hasMany(Comment::class, 'target_user_id');
    }
}
