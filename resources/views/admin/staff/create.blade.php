@extends('layouts.admin')

@section('title', 'Tambah Akun Staf Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <a href="{{ route('admin.staff.index') }}" class="text-xs text-[#B5753A] font-bold uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Daftar Staf
        </a>
        <h1 class="text-2xl font-display font-semibold text-[#2B1D14] tracking-tight">Tambah Akun Staf / Kasir Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.staff.store') }}"
          class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-sm space-y-5">
        @csrf

        <!-- Nama Pegawai -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Nama Lengkap <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Contoh: Rian Barista"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Alamat Email <span class="text-[#B4552F]">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="rian@coffebar.id"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Nomor WhatsApp / HP <span class="text-[#B4552F]">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                       placeholder="08123456789"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>
        </div>

        <!-- Role -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Peran / Hak Akses <span class="text-[#B4552F]">*</span>
            </label>
            <select name="role" required
                    class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none font-semibold">
                <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Kasir / Barista (Akses Dashboard Kasir, POS, Verifikasi QR, Shift)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Owner / Administrator (Akses Penuh Seluruh Sistem)</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-dashed border-[#CDB48F]">
            <!-- Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Kata Sandi <span class="text-[#B4552F]">*</span>
                </label>
                <input type="password" name="password" required minlength="8"
                       placeholder="Minimal 8 karakter"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Ulangi Kata Sandi <span class="text-[#B4552F]">*</span>
                </label>
                <input type="password" name="password_confirmation" required minlength="8"
                       placeholder="Ketik ulang kata sandi"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>
        </div>

        <div class="p-3.5 bg-[#E8DCC4] border border-[#CDB48F] rounded-xl text-xs text-[#4A3224]">
            ℹ️ Akun staf yang dibuat langsung otomatis terverifikasi dan dapat digunakan untuk masuk ke sistem tanpa menunggu OTP.
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.staff.index') }}"
               class="px-5 py-2.5 border border-[#CDB48F] text-[#4A3224] text-xs uppercase tracking-wider font-semibold rounded-xl hover:bg-[#E8DCC4] transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition cursor-pointer">
                Simpan Akun Staf
            </button>
        </div>
    </form>
</div>
@endsection
