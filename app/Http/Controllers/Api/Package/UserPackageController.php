<?php

namespace App\Http\Controllers\Api\Package;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Packages;

class UserPackageController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();

        $packages = Packages::with('classes')
            ->latest()
            ->get()
            ->map(function ($package) use ($user) {

                $isOwned = DB::table('user_packages')
                    ->where('user_id', $user->id)
                    ->where('package_id', $package->id)
                    ->exists();

                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price' => $package->price,
                    'image' => $package->image ? asset('storage/'.$package->image) : null,
                    'totalClass' => $package->classes->count(),

                    // 🔥 INI PENTING
                    'is_owned' => $isOwned
                ];
            });

        return response()->json([
            'data' => $packages
        ]);
    }
    
    public function myClasses(Request $request)
    {
        $user = $request->user();

        $classes = ClassRoom::whereIn('id', function ($query) use ($user) {
            $query->select('class_id')
                ->from('package_classes')
                ->whereIn('package_id', function ($q) use ($user) {
                    $q->select('package_id')
                        ->from('user_packages')
                        ->where('user_id', $user->id);
                });
        })
        ->with('chapters')
        ->get();

        return response()->json([
            'data' => $classes
        ]);
    }
    public function buy(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        // cek sudah beli
        $exists = DB::table('user_packages')
            ->where('user_id', $user->id)
            ->where('package_id', $request->package_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Paket sudah dibeli'
            ], 400);
        }

        DB::table('user_packages')->insert([
            'user_id' => $user->id,
            'package_id' => $request->package_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Berhasil membeli paket'
        ]);
    }
}
