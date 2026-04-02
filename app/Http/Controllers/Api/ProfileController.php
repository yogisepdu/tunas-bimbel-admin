<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProfileSiswa;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    // 🔥 GET PROFILE
    public function me(Request $request)
    {
        $user = $request->user();

        $profile = $user->profile;

        return response()->json([
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    // 🔥 UPDATE / CREATE PROFILE
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|in:pria,wanita',
            'province_id' => 'nullable',
            'regency_id' => 'nullable',
            'district_id' => 'nullable',
            'village_id' => 'nullable',
        ]);

        // 🔥 CREATE ATAU UPDATE
        $profile = ProfileSiswa::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'Profil berhasil disimpan',
            'profile' => $profile,
        ]);
    }
}
