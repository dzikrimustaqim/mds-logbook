<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Kita tetap menggunakan 'email' di sini, tapi akan diinterpretasikan sebagai 'username'
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // UBAH LOGIKA INI
        // Kita gunakan Auth::attempt() dan secara eksplisit set kolom 'username'
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            
            // Coba lagi menggunakan 'username' sebagai pengganti 'email'
            if (! Auth::attempt([
                'username' => $this->get('email'), // 'email' yang diinput user adalah 'username'
                'password' => $this->get('password')
            ], $this->boolean('remember'))) {
            
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
        }
        
        RateLimiter::clear($this->throttleKey());
    }

    // ... method lainnya tetap sama ...
    
    /**
     * Get the throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}