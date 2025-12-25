<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'foto',
        'status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
