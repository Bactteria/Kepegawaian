<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'email', 'jabatan', 'gender', 'tanggal_lahir', 'telepon', 'alamat', 'foto', 'unit_kerja', 'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    public function staffs()
    {
        return $this->hasMany(self::class, 'manager_id');
    }

}
