<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Organization extends Model
{
    //

    public function services(): HasMany
{
    return $this->hasMany(Service::class)->chaperone();
}

    public function districts(): HasMany
{
    return $this->hasMany(District::class)->chaperone();
}

}
