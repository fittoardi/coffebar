@extends('layouts.admin')

@section('title', 'Manajemen Akun Staf & Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-copper/30 pb-4 gap-4">
        <div>
            <span class="text-xs uppercase font-heading tracking-widest text-copper block">Otoritas & Akses Internal</span>
            <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Akun Staf & Kasir</h1>
        </div>
        <div>
            <a href="{{ route('admin.staff.create') }}"
               class="px-4 py-2.5 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Akun Staf Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 shadow-rustic-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b-2 border-rustic-800 bg-rustic-100 font-heading uppercase tracking-wider text-rustic-800">
                        <th class="p-3">Nama Pegawai</th>
                        <th class="p-3">Peran (Role)</th>
                        <th class="p-3">Email & No. HP</th>
                        <th class="p-3 text-center">Status Akun</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rustic-200">
                    @foreach ($staffMembers as $staff)
                        <tr class="hover:bg-rustic-50/70 transition">
                            <td class="p-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-rustic-800 text-parchment font-heading font-black text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong class="text-rustic-900 font-display text-sm">{{ $staff->name }}</strong>
                                        @if ($staff->id === Auth::id())
                                            <span class="ml-1 text-[10px] text-copper font-heading uppercase font-bold">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-3">
                                @if ($staff->role === 'admin')
                                    <span class="px-2.5 py-1 bg-brick text-parchment font-heading uppercase text-[10px] font-bold">
                                        Owner / Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-copper text-parchment font-heading uppercase text-[10px] font-bold">
                                        Kasir / Barista
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="font-mono text-rustic-900">{{ $staff->email }}</div>
                                <div class="text-[11px] text-rustic-600 font-mono">{{ $staff->phone }}</div>
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-300 font-heading text-[10px] uppercase font-bold">
                                    ✓ Aktif & Terverifikasi
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('admin.staff.edit', $staff) }}"
                                   class="px-2.5 py-1 bg-rustic-100 text-rustic-800 border border-rustic-300 font-heading text-[11px] font-bold hover:bg-copper hover:text-parchment hover:border-copper transition">
                                    Ubah
                                </a>

                                @if ($staff->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staf ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 bg-brick/10 text-brick border border-brick/40 font-heading text-[11px] font-bold hover:bg-brick hover:text-parchment transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
