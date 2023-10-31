<?php

namespace App\Models;

use App\Enums\UserGenders;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Profile extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    public const MEDIA_COLLECTION_NAME = 'avatar';
    public const MEDIA_DISK_NAME = 'avatars';

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'phone_number',
        'address',
        'dob',
        'gender',
        'user_id',
    ];
    protected $casts = [
        'dob' => 'datetime',
        'gender' => UserGenders::class,
    ];

    /*
    |------------------------------------------------------------------|
    |Relations
    |------------------------------------------------------------------|
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    |
    */

    public function setFirstNameAttribute($value): void
    {
        $this->attributes['first_name'] = ucwords($value);
        $this->setFullNameAttribute();
    }

    public function setFullNameAttribute(): void
    {
        $this->attributes['full_name'] = $this->first_name . ' ' . $this->last_name;
    }

    public function setLastNameAttribute($value): void
    {
        $this->attributes['last_name'] = ucwords($value);
        $this->setFullNameAttribute();

    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_NAME)
            ->singleFile()
            ->useDisk(self::MEDIA_DISK_NAME)
            ->useFallbackUrl(asset('images/default/avatar.png'));
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        //avatar
        $this->addMediaConversion('thumb')
            ->crop('crop-center', '180', '180')
            ->performOnCollections(self::MEDIA_COLLECTION_NAME)
            ->quality(70);

        $this->addMediaConversion('square')
            ->crop('crop-center', '250', '250')
            ->performOnCollections(self::MEDIA_COLLECTION_NAME)
            ->quality(70);
    }


    #[ArrayShape(['original' => "string", 'thumb' => "string", 'square' => "string"])]
    public function getAvatarAttribute(): array
    {
        $media = $this->getFirstMedia(self::MEDIA_COLLECTION_NAME);

        return [
            'original' => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME),
            'thumb' => !$media ? $this->getFallbackMediaUrl(self::MEDIA_COLLECTION_NAME) : $media->getUrl('thumb'),
            'square' => !$media ? $this->getFallbackMediaUrl(self::MEDIA_COLLECTION_NAME) : $media->getUrl('square'),
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getGenderAttribute($gender): int
    {
        return $gender === UserGenders::MAN->value ?
            UserGenders::MAN->value :
            UserGenders::WOMAN->value;
    }

    /**
     * Accessor for Age.
     */
    public function age(): int
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

}
