<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Matikan timestamps otomatis Laravel
    public $timestamps = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'phone',
        'address',
        'id_card_number',
        'emergency_contact',
        'company_code',
        'status',
        'is_deleted',
        'created_by',
        'created_date',
        'last_updated_by',
        'last_updated_date',
    ];

    protected $hidden = [
        'password',
    ];
}