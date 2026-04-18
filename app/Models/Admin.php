<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'can_edit', 'avatar',
        'last_login', 'last_login_ip',
        'company_code', 'status', 'is_deleted',
        'created_by', 'created_date',
        'last_updated_by', 'last_updated_date',
        'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $hidden = ['password'];

    // ✅ Helper: cek apakah admin/superadmin
    public function isAdminOrSuperAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    // ✅ Helper: cek apakah bisa edit
    public function canEdit(): bool
    {
        if ($this->isAdminOrSuperAdmin()) return true;
        return (bool) $this->can_edit;
    }
}