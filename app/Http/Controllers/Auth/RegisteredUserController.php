<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'avatar' => ['required', 'image', 'mimes:png, jpg, jpeg'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // proses upload file photo kepada projek laravel ini
        if($request->hasFile('avatar')) { // jika request(form) tersebut menerima sebuah file dari input typenya file dan namenya avatar, maka
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); // dapatkan path avatar, lalu simpan ke folder avatars dan simpan secara public agar bisa diakses di projek laravel ini
        } else { // kalau user lupa mengupload avatar, maka akan diisi dengan avatar default
            $avatarPath = 'images/avatar-default.png';
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'occupation' => $request->occupation,
            'avatar' => $request->avatarPath,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
