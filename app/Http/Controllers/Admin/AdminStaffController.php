<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStaffController extends Controller
{
    public function index(): View
    {
        $staffMembers = User::query()
            ->whereIn('role', ['admin', 'cashier'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.staff.index', compact('staffMembers'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'role' => ['required', 'string', 'in:admin,cashier'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staf baru berhasil dibuat.');
    }

    public function edit(User $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone' => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($staff->id)],
            'role' => ['required', 'string', 'in:admin,cashier'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staf "'.$staff->name.'" berhasil diperbarui.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if (Auth::id() === $staff->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $staff->name;
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staf "'.$name.'" berhasil dihapus.');
    }
}
