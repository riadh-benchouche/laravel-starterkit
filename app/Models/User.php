<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * User.
 *
 * @OA\Schema(
 *     title="Users",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity, SearchableTrait;

    /**
     * @OA\Property(
     *     format="int64",
     *     title="id",
     *     default=1,
     *     description="id",
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="User email",
     *     description="User email"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="User password",
     *     description="User password"
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *     title="User status",
     *     description="User status"
     * )
     *
     * @var Status
     */
    private $user_status;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'notification_status',
        'user_status',
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

    protected $searchable = [
        'columns' => [
            'users.email' => 10,
            'profiles.full_name' => 9,
            'profiles.address' => 8,
        ],
        'joins' => [
            'profiles' => ['users.id', 'profiles.user_id'],
        ],
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_status' => Status::class,
        'notification_status' => 'boolean',
    ];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::Class, 'user_id')->withTrashed();
    }

    /**
     * @param string|null $password
     */
    public function setPasswordAttribute(?string $password): void
    {
        if (!$password) {
            $this->attributes['password'] = null;
            return;
        }
        $this->attributes['password'] = Hash::needsRehash($password)
            ? Hash::make($password)
            : $password;
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
            ->logOnly(['name', 'email'])
            ->logOnlyDirty();
    }
}
