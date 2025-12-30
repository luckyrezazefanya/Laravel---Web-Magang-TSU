<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('pages.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nim'      => 'required|digits:8|unique:mahasiswa,nim',
            'email'    => 'required|email:dns|unique:users,email',//promp custom @tsu.ac.id
            'password' => 'required|confirmed|min:10',
            'role'     => 'required|in:mahasiswa,dosen',
        ]);


        DB::transaction(function () use ($request) {

            // INSERT KE USERS
            $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => $request->role,
                    ]);

                    Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim'     => $request->nim,
                    ]);

        });

        return redirect('/login')->with('success', 'Registrasi berhasil');
    }
}
