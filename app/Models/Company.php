<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'logo_url', 'description', 'address',
        'operational_hours', 'phone', 'email', 'jalur', 'kuota', 'alumni_magang',
    ];

    public function internshipApplications()
    {
        return $this->hasMany(InternshipApplication::class);
    }
}
