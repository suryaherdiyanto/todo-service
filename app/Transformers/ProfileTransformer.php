<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Profile;

class ProfileTransformer extends TransformerAbstract {

    public function transform(Profile $profile)
    {
        return [
            'id' => $profile->id,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'phone_number' => $profile->phone_number,
            'country' => $profile->country,
            'state' => $profile->state,
            'address' => $profile->address,
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at
        ];
    }

}