<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Loan;
use App\Models\Fine;
use Illuminate\Http\Request;

class OpacMemberController extends Controller
{
    /**
     * Show member login / area keanggotaan page.
     */
    public function showMemberArea()
    {
        if (session('opac_member_id')) {
            return redirect()->route('opac.member.dashboard');
        }
        return view('opac.member.login');
    }

    /**
     * Handle member login via member_id + PIN (or mpasswd MD5).
     */
    public function login(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string|max:20',
            'pin'       => 'required|string',
        ], [
            'member_id.required' => 'Nomor Anggota wajib diisi.',
            'pin.required'       => 'PIN / Password wajib diisi.',
        ]);

        $member = Member::where('member_id', $request->member_id)
                        ->where('is_pending', 0)
                        ->first();

        if (!$member) {
            return back()->withErrors(['member_id' => 'Nomor anggota tidak ditemukan atau akun belum diaktifkan.'])->withInput();
        }

        $pinMatches     = ($member->pin && (string)$member->pin === (string)$request->pin);
        $mpasswdMatches = ($member->mpasswd && md5($request->pin) === $member->mpasswd);

        if (!$pinMatches && !$mpasswdMatches) {
            return back()->withErrors(['pin' => 'PIN / Password yang Anda masukkan salah.'])->withInput();
        }

        if ($member->expire_date && $member->expire_date->isPast()) {
            return back()->withErrors(['member_id' => 'Keanggotaan Anda telah kedaluwarsa. Hubungi pustakawan untuk memperpanjang.'])->withInput();
        }

        session([
            'opac_member_id'   => $member->member_id,
            'opac_member_name' => $member->member_name,
        ]);

        $member->last_login    = now();
        $member->last_login_ip = $request->ip();
        $member->save();

        return redirect()->route('opac.member.dashboard')
                         ->with('success', 'Selamat datang, ' . $member->member_name . '!');
    }

    /**
     * Member dashboard: profile, active loans, history, fines.
     */
    public function dashboard()
    {
        $memberId = session('opac_member_id');
        if (!$memberId) {
            return redirect()->route('opac.member.area')
                             ->withErrors(['member_id' => 'Silakan login terlebih dahulu.']);
        }

        $member = Member::with(['memberType'])->findOrFail($memberId);

        $activeLoans = Loan::with(['item.biblio'])
            ->where('member_id', $memberId)
            ->whereNull('return_date')
            ->orderBy('due_date', 'asc')
            ->get();

        $loanHistory = Loan::with(['item.biblio'])
            ->where('member_id', $memberId)
            ->whereNotNull('return_date')
            ->orderBy('return_date', 'desc')
            ->take(20)
            ->get();

        $fines      = Fine::where('member_id', $memberId)->whereRaw('credit < debet')->get();
        $totalFines = $fines->sum('debet') - $fines->sum('credit');

        return view('opac.member.dashboard', compact(
            'member', 'activeLoans', 'loanHistory', 'fines', 'totalFines'
        ));
    }

    /**
     * Logout member from OPAC session.
     */
    public function logout()
    {
        session()->forget(['opac_member_id', 'opac_member_name']);
        return redirect()->route('opac.member.area')
                         ->with('success', 'Anda telah berhasil keluar dari Area Keanggotaan.');
    }
}
