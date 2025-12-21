<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class ManagementApiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua manager dan staff seperti di KaryawanController@managementLevel
        $managers = Karyawan::where('jabatan', 'like', '%manager%')->orderBy('nama')->get();
        $staffAll = Karyawan::where('jabatan', 'like', '%staff%')->orderBy('nama')->get();

        $groups = $managers->map(function ($manager) use ($staffAll) {
            $staffWithManagerId = $manager->staffs()->orderBy('nama')->get();

            $staffByUnitKerja = $staffAll
                ->whereNull('manager_id')
                ->where('unit_kerja', $manager->unit_kerja);

            $mergedStaff = $staffWithManagerId->concat($staffByUnitKerja)->unique('id');

            return [
                'manager' => [
                    'id' => $manager->id,
                    'nama' => $manager->nama,
                    'jabatan' => $manager->jabatan,
                    'unit_kerja' => $manager->unit_kerja,
                ],
                'staff' => $mergedStaff->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'nama' => $s->nama,
                        'jabatan' => $s->jabatan,
                        'unit_kerja' => $s->unit_kerja,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'data' => $groups,
        ]);
    }
}
