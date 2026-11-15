<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('sort_order')->get();

        $query = Product::query()->with(['category', 'options']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'available') {
                $query->where('is_available', true);
            } elseif ($status === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $products = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1500'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*.type' => ['required', 'string', 'in:size,addon,sugar,ice,base,topping'],
            'options.*.name' => ['required', 'string', 'max:100'],
            'options.*.price' => ['required', 'numeric', 'min:0'],
            'options.*.is_available' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_available'] = $request->boolean('is_available', true);

        /** @var Product $product */
        $product = Product::query()->create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'is_available' => $validated['is_available'],
            'sort_order' => $validated['sort_order'],
        ]);

        if (! empty($validated['options'])) {
            foreach ($validated['options'] as $optionData) {
                if (! empty($optionData['name'])) {
                    $product->options()->create([
                        'type' => $optionData['type'],
                        'name' => $optionData['name'],
                        'price' => $optionData['price'] ?? 0,
                        'is_available' => ! empty($optionData['is_available']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('sort_order')->get();
        $product->load('options');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1500'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.type' => ['required', 'string', 'in:size,addon,sugar,ice,base,topping'],
            'options.*.name' => ['required', 'string', 'max:100'],
            'options.*.price' => ['required', 'numeric', 'min:0'],
            'options.*.is_available' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::query()->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_available'] = $request->boolean('is_available');

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? $product->image,
            'is_available' => $validated['is_available'],
            'sort_order' => $validated['sort_order'],
        ]);

        // Sync options
        if (isset($validated['options'])) {
            $existingOptionIds = [];
            foreach ($validated['options'] as $opt) {
                if (empty($opt['name'])) {
                    continue;
                }

                if (! empty($opt['id'])) {
                    $existingOption = ProductOption::query()
                        ->where('product_id', $product->id)
                        ->find($opt['id']);

                    if ($existingOption) {
                        $existingOption->update([
                            'type' => $opt['type'],
                            'name' => $opt['name'],
                            'price' => $opt['price'] ?? 0,
                            'is_available' => ! empty($opt['is_available']),
                        ]);
                        $existingOptionIds[] = $existingOption->id;
                    }
                } else {
                    $newOption = $product->options()->create([
                        'type' => $opt['type'],
                        'name' => $opt['name'],
                        'price' => $opt['price'] ?? 0,
                        'is_available' => ! empty($opt['is_available']),
                    ]);
                    $existingOptionIds[] = $newOption->id;
                }
            }

            // Remove options that were deleted in the UI
            $product->options()->whereNotIn('id', $existingOptionIds)->delete();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil diperbarui.');
    }

    public function toggleAvailability(Product $product): RedirectResponse
    {
        $product->is_available = ! $product->is_available;
        $product->save();

        $statusStr = $product->is_available ? 'Tersedia' : 'Habis';

        return back()->with('success', "Status produk '{$product->name}' diubah menjadi: {$statusStr}");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$name.'" berhasil dihapus.');
    }
}
