<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class District extends Model
{
     protected $fillable = ['name','created_at','updated_at'];
      public function organization(): BelongsTo
{
    return $this->belongsTo(Organization::class);
}
}
