<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Service extends Model
{
    //mass assaignment

      protected $fillable = ['organization_id','district_id','name', 'description','service_type', 'service_scope', 'type_of_beneficiary', 'number_of_beneficiary',
                            'unique_services', 'number_service_location', 'challenges_faced'
                          ];

    public function organization(): BelongsTo
{
    return $this->belongsTo(Organization::class);
}
public function district(): BelongsTo
{
    return $this->belongsTo(District::class);
}
public function beneficiary(): BelongsTo
{
    return $this->belongsTo(Beneficiary::class);
}



}
