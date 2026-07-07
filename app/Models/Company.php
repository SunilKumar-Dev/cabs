<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;


#[Fillable([    
        'id',
        'company_uuid',
        'company_name',
        'company_slug',
        'company_description',
        'company_pan_no',
        'company_gst_no',
        'company_registration_no',
        'company_email',
        'company_phone',
        'company_website',
        'company_address_line_1',
        'company_address_line_2',
        'company_city',
        'company_state',
        'company_country',
        'company_postal_code',
        'company_logo',
        'company_favicon',
        'created_by_uuid',
        'status',
])]
class Company extends Model
{
     use HasFactory, Notifiable, SoftDeletes;

     protected $guarded = [];

 

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function getLogoUrlAttribute()
    {
        return $this->company_logo ? asset('storage/' . $this->company_logo) : null;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->company_favicon ? asset('storage/' . $this->company_favicon) : null;
    }
    


}
