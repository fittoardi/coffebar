@extends('layouts.admin')

@section('title', 'Manajemen Akun Staf & Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header (03-DESIGN.md Section 19) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-dashed border-[#CDB48F] pb-4 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Otoritas & Akses Internal</span>
            <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Akun Staf & Kasir</h1>
        </div>
        <div>
            <a href="{{ route('admin.staff.create') }}"
               class="px-5 py-2.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Akun Staf Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card (Oat table header, Paper body, Kraft border) -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-[#E8DCC4] uppercase tracking-wider text-[#4A3224] text-[10px] font-bold">
                        <th class="p-3.5">Nama Pegawai</th>
                        <th class="p-3.5">Peran (Role)</th>
                        <th class="p-3.5">Email & No. HP</th>
                        <th class="p-3.5 text-center">Status Akun</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                    @foreach ($staffMembers as $staff)
                        <tr class="hover:bg-[#F4ECDD]/50 transition">
                            <td class="p-3.5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-[#2B1D14] text-[#F4ECDD] font-display font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong class="text-[#2B1D14] font-display text-sm font-semibold">{{ $staff->name }}</strong>
                                        @if ($staff->id === Auth::id())
                                            <span class="ml-1 text-[10px] text-[#B5753A] uppercase font-bold">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5">
                                @if ($staff->role === 'admin')
                                    <span class="px-2.5 py-1 bg-[#B4552F] text-white uppercase text-[10px] font-bold rounded-full">
                                        Owner / Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-[#B5753A] text-white uppercase text-[10px] font-bold rounded-full">
                                        Kasir / Barista
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <div class="font-mono text-[#2B1D14] font-medium">{{ $staff->email }}</div>
                                <div class="text-[11px] text-[#6B5646] font-mono">{{ $staff->phone }}</div>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 bg-[#7A8467]/20 text-[#4F5A3E] border border-[#7A8467] text-[10px] uppercase font-bold rounded-full">
                                    ✓ Aktif & Terverifikasi
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-2">
                                <a href="{{ route('admin.staff.edit', $staff) }}"
                                   class="px-3 py-1.5 bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] text-[11px] font-bold uppercase rounded-lg hover:bg-[#E8DCC4] transition">
                                    Ubah
                                </a>

                                @if ($staff->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staf ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-transparent text-[#B4552F] border border-[#B4552F] text-[11px] font-bold uppercase rounded-lg hover:bg-[#B4552F] hover:text-white transition cursor-pointer">
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
