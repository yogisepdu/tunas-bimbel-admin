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

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:pria,wanita',
            'province_id' => 'nullable|integer',
            'regency_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'village_id' => 'nullable|integer',
        ]);

        $profile = $user->profile()->updateOrCreate(
            [],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil disimpan',
            'profile' => $profile,
        ]);
    }
}
