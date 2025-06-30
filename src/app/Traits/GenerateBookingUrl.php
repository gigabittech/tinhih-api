<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GenerateBookingUrl
{
    protected static function bootGenerateBookingUrl()
    {
        static::creating(function ($model) {
                $model->booking_url = static::generateBookingUrl($model->businessName);
        });
    }



    public static function generateBookingUrl($businessName)
    {
        $baseSlug = Str::slug($businessName);
        $slug = $baseSlug;
        $i = 1;

        // Assuming model using this trait has 'booking_url' field
        while (static::where('booking_url', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        return $slug;
    }
}
