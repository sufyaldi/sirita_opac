<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetActiveBranch
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Jika ada request untuk mengubah cabang aktif
            if ($request->has('switch_branch')) {
                $targetBranchId = (int) $request->query('switch_branch');
                $allowedIds = $user->allowedBranchIds();
                
                if (in_array($targetBranchId, $allowedIds)) {
                    session(['active_branch_id' => $targetBranchId]);
                }
            }
            
            // Pemicu fallback inisialisasi session jika belum diset
            $user->activeBranchId();
        }

        return $next($request);
    }
}
