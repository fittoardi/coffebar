@extends('layouts.admin')

@section('title', 'Ubah Akun Staf — ' . $staff->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <a href="{{ route('admin.staff.index') }}" class="text-xs text-[#B5753A] font-bold uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Daftar Staf
        </a>
        <h1 class="text-2xl font-display font-semibold text-[#2B1D14] tracking-tight">Ubah Akun: {{ $staff->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.staff.update', $staff) }}"
          class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama Pegawai -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Nama Lengkap <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $staff->name) }}" required
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Alamat Email <span class="text-[#B4552F]">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}" required
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Nomor WhatsApp / HP <span class="text-[#B4552F]">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" required
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
                <option value="cashier" {{ old('role', $staff->role) === 'cashier' ? 'selected' : '' }}>Kasir / Barista (Akses Dashboard Kasir, POS, Verifikasi QR, Shift)</option>
                <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>Owner / Administrator (Akses Penuh Seluruh Sistem)</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-dashed border-[#CDB48F]">
            <!-- Password Baru -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Ganti Kata Sandi (Opsional)
                </label>
                <input type="password" name="password" minlength="8"
                       placeholder="Kosongkan bila tidak ingin diubah"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Ulangi Kata Sandi Baru
                </label>
                <input type="password" name="password_confirmation" minlength="8"
                       placeholder="Ulangi kata sandi baru"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.staff.index') }}"
               class="px-5 py-2.5 border border-[#CDB48F] text-[#4A3224] text-xs uppercase tracking-wider font-semibold rounded-xl hover:bg-[#E8DCC4] transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition cursor-pointer">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
