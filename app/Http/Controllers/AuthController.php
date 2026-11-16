<?php

namespace App\Http\Controllers;

use App\Exceptions\OtpInvalidException;
use App\Exceptions\OtpRateLimitException;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Display registration page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar. Silakan masuk.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp / HP sudah terdaftar. Silakan masuk.',
        ]);

        // Normalize Indonesian phone number (e.g. +62 to 08)
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        if (str_starts_with($cleanPhone, '62')) {
            $cleanPhone = '0'.substr($cleanPhone, 2);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $cleanPhone,
            'role' => 'customer',
            'password' => null,
        ]);

        try {
            $this->otpService->generateForRegistration($user->email, $user->phone, $user->id);
        } catch (OtpRateLimitException $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }

        if ($request->hasSession()) {
            $request->session()->put('pending_otp_identifier', $user->email);
            $request->session()->put('pending_otp_phone', $user->phone);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Registrasi berhasil. Kode OTP telah dikirimkan ke email dan nomor HP Anda.',
                'identifier' => $user->email,
                'phone' => $user->phone,
            ]);
        }

        return redirect()->route('otp.verify.notice')
            ->with('status', 'Kode OTP telah dikirimkan ke email dan nomor HP Anda. Silakan masukkan kode verifikasi.');
    }

    /**
     * Display customer login page (OTP only).
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.customer-login');
    }

    /**
     * Display staff & admin internal login page (Email + Password).
     */
    public function showStaffLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            return $this->redirectBasedOnRole($user);
        }

        return view('auth.staff-login');
    }

    /**
     * Handle staff internal login via password.
     */
    public function staffLogin(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau Akun Petugas wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $identifier = trim($validated['email']);
        $password = $validated['password'];

        $user = User::where('email', strtolower($identifier))
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user || ! Hash::check($password, (string) $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial staf yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        if (! in_array($user->role, ['admin', 'cashier'], true)) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini terdaftar sebagai pelanggan. Silakan masuk melalui halaman login pelanggan.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        if ($request->wantsJson()) {
            $token = $user->createToken('staff-token')->plainTextToken;

            return response()->json([
                'message' => 'Login staf berhasil.',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Handle login (either via password for staff or OTP for customers).
     */
    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['nullable', 'string'],
        ], [
            'identifier.required' => 'Email atau Nomor HP wajib diisi.',
        ]);

        $identifier = trim($validated['identifier']);
        $password = $validated['password'] ?? null;

        // Find user by email or phone
        $user = User::where('email', strtolower($identifier))
            ->orWhere('phone', $identifier)
            ->first();

        // If password is provided (e.g. Cashier or Admin login)
        if (! empty($password)) {
            if (! $user || ! Hash::check($password, (string) $user->password)) {
                throw ValidationException::withMessages([
                    'identifier' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
                ]);
            }

            Auth::login($user, true);

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            if ($request->wantsJson()) {
                $token = $user->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'message' => 'Login berhasil.',
                    'token' => $token,
                    'user' => $user,
                ]);
            }

            return $this->redirectBasedOnRole($user);
        }

        // Standard Customer OTP Login
        if (! $user) {
            throw ValidationException::withMessages([
                'identifier' => 'Akun dengan email atau nomor HP tersebut belum terdaftar. Silakan daftar terlebih dahulu.',
            ]);
        }

        // Generate OTP
        $channel = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'whatsapp';

        try {
            $this->otpService->generate($identifier, $channel, $user->id);
        } catch (OtpRateLimitException $e) {
            throw ValidationException::withMessages([
                'identifier' => $e->getMessage(),
            ]);
        }

        if ($request->hasSession()) {
            $request->session()->put('pending_otp_identifier', $identifier);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kode OTP telah dikirimkan. Silakan periksa pesan Anda.',
                'identifier' => $identifier,
            ]);
        }

        return redirect()->route('otp.verify.notice')
            ->with('status', 'Kode OTP 6-digit telah dikirim ke '.$identifier.'. Masukkan kode untuk masuk.');
    }

    /**
     * Display OTP verification page.
     */
    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        $identifier = ($request->hasSession() ? $request->session()->get('pending_otp_identifier') : null) ?? $request->query('identifier');

        return view('auth.verify-otp', [
            'identifier' => $identifier,
        ]);
    }

    /**
     * Process OTP verification.
     */
    public function verifyOtp(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'code' => ['required', 'string', 'min:6', 'max:6'],
        ], [
            'identifier.required' => 'Identitas akun tidak ditemukan.',
            'code.required' => 'Kode OTP 6-digit wajib diisi.',
            'code.min' => 'Kode OTP harus berupa 6 angka.',
            'code.max' => 'Kode OTP harus berupa 6 angka.',
        ]);

        $identifier = trim($validated['identifier']);
        $code = trim($validated['code']);

        try {
            $this->otpService->verify($identifier, $code);
        } catch (OtpInvalidException $e) {
            throw ValidationException::withMessages([
                'code' => $e->getMessage(),
            ]);
        }

        // Find user and log in
        $user = User::where('email', strtolower($identifier))
            ->orWhere('phone', $identifier)
            ->firstOrFail();

        Auth::login($user, true);

        if ($request->hasSession()) {
            $request->session()->forget(['pending_otp_identifier', 'pending_otp_phone']);
            $request->session()->regenerate();
        }

        if ($request->wantsJson()) {
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Verifikasi berhasil. Selamat datang di CoffeBar!',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return $this->redirectBasedOnRole($user)
            ->with('success', 'Selamat datang kembali, '.$user->name.'!');
    }

    /**
     * Resend OTP code.
     */
    public function resendOtp(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $identifier = trim($validated['identifier']);
        $channel = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'whatsapp';

        $user = User::where('email', strtolower($identifier))
            ->orWhere('phone', $identifier)
            ->first();

        try {
            $this->otpService->generate($identifier, $channel, $user?->id);
        } catch (OtpRateLimitException $e) {
            throw ValidationException::withMessages([
                'identifier' => $e->getMessage(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kode OTP baru telah berhasil dikirimkan.',
                'identifier' => $identifier,
            ]);
        }

        return back()->with('status', 'Kode verifikasi baru telah dikirimkan ke '.$identifier);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user() && $request->wantsJson()) {
            $request->user()->currentAccessToken()?->delete();

            return response()->json(['message' => 'Berhasil keluar.']);
        }

        Auth::logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/')->with('status', 'Anda telah berhasil keluar.');
    }

    /**
     * Helper to redirect user to proper home dashboard based on role.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended('/admin');
        }

        if ($user->isCashier()) {
            return redirect()->intended('/kasir');
        }

        return redirect()->intended('/');
    }
}
