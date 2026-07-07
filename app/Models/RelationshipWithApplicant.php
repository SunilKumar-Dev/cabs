<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationshipWithApplicant extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];
}
