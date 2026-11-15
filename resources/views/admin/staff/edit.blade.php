@extends('layouts.admin')

@section('title', 'Ubah Akun Staf — ' . $staff->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b-2 border-copper/30 pb-4">
        <a href="{{ route('admin.staff.index') }}" class="text-xs font-heading text-copper uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Daftar Staf
        </a>
        <h1 class="text-2xl font-display font-black text-rustic-900 tracking-tight">Ubah Akun: {{ $staff->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.staff.update', $staff) }}"
          class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama Pegawai -->
        <div>
            <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                Nama Lengkap <span class="text-brick">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $staff->name) }}" required
                   class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Alamat Email <span class="text-brick">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}" required
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Nomor WhatsApp / HP <span class="text-brick">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" required
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-mono text-rustic-900 focus:border-copper focus:outline-none">
            </div>
        </div>

        <!-- Role -->
        <div>
            <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                Peran / Hak Akses <span class="text-brick">*</span>
            </label>
            <select name="role" required
                    class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
                <option value="cashier" {{ old('role', $staff->role) === 'cashier' ? 'selected' : '' }}>Kasir / Barista (Akses Dashboard Kasir, POS, Verifikasi QR, Shift)</option>
                <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>Owner / Administrator (Akses Penuh Seluruh Sistem)</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-rustic-200">
            <!-- Password Baru -->
            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Ganti Kata Sandi (Opsional)
                </label>
                <input type="password" name="password" minlength="8"
                       placeholder="Kosongkan bila tidak ingin diubah"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Ulangi Kata Sandi Baru
                </label>
                <input type="password" name="password_confirmation" minlength="8"
                       placeholder="Ulangi kata sandi baru"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.staff.index') }}"
               class="px-4 py-2 border border-rustic-300 text-rustic-700 font-heading text-xs uppercase tracking-wider hover:bg-rustic-100 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
