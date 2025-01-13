<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Service extends Model
{
    //mass assaignment

      protected $fillable = ['organization_id','district_id','areas','name', 'description','number_of_beneficiary',
                            'start_date', 'end_date',
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
