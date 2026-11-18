<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kopi',
                'slug' => 'kopi',
                'description' => 'Seduhan biji kopi pilihan lokal dan mancanegara dengan cita rasa autentik.',
                'icon' => 'coffee',
                'sort_order' => 1,
                'products' => [
                    [
                        'name' => 'Espresso',
                        'description' => 'Single/double shot espresso murni dengan crema tebal dan aroma pekat.',
                        'price' => 18000,
                    ],
                    [
                        'name' => 'Americano',
                        'description' => 'Espresso kuat yang dipadukan dengan air hangat atau es batu segar.',
                        'price' => 22000,
                    ],
                    [
                        'name' => 'Cafe Latte',
                        'description' => 'Espresso berpadu dengan steamed milk lembut dan microfoam halus.',
                        'price' => 26000,
                    ],
                    [
                        'name' => 'Caramel Latte',
                        'description' => 'Espresso lembut dengan susu karamel gurih manis dan siraman saus karamel tembaga.',
                        'price' => 28000,
                    ],
                    [
                        'name' => 'Kopi Susu Gula Aren',
                        'description' => 'Kopi susu khas Nusantara dengan racikan gula aren murni berkualitas tinggi.',
                        'price' => 24000,
                    ],
                    [
                        'name' => 'Cold Brew Classic',
                        'description' => 'Kopi dingin yang diseduh lambat selama 16 jam, rendah asam dan kaya rasa.',
                        'price' => 25000,
                    ],
                ],
            ],
            [
                'name' => 'Non-Kopi',
                'slug' => 'non-kopi',
                'description' => 'Pilihan minuman segar berbasis cokelat, susu, dan teh artisan premium.',
                'icon' => 'sparkles',
                'sort_order' => 2,
                'products' => [
                    [
                        'name' => 'Matcha Fusion Latte',
                        'description' => 'Matcha murni Uji Kyoto dipadukan susu creamy segar beraroma menenangkan.',
                        'price' => 28000,
                    ],
                    [
                        'name' => 'Artisan Dark Chocolate',
                        'description' => 'Cokelat hitam pekat 70% dengan susu hangat creamy dan sensasi manis seimbang.',
                        'price' => 26000,
                    ],
                    [
                        'name' => 'Earl Grey Milk Tea',
                        'description' => 'Teh hitam Earl Grey beraroma bergamot khas dengan racikan susu lembut.',
                        'price' => 24000,
                    ],
                    [
                        'name' => 'Peach Sparkling Tea',
                        'description' => 'Seduhan teh buah persik dengan soda segar menyegarkan tenggorokan.',
                        'price' => 25000,
                    ],
                ],
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'description' => 'Sajian utama mengenyangkan yang nikmat disantap menemani seduhan kopi.',
                'icon' => 'cake',
                'sort_order' => 3,
                'products' => [
                    [
                        'name' => 'Butter Croissant',
                        'description' => 'Pastry Prancis berlapir mentega renyah di luar dan lembut wangi di dalam.',
                        'price' => 18000,
                    ],
                    [
                        'name' => 'Almond Croissant',
                        'description' => 'Croissant isi krim almond lembut dengan taburan kacang almond panggang gurih.',
                        'price' => 24000,
                    ],
                    [
                        'name' => 'Roti Bakar Coklat Keju',
                        'description' => 'Roti brioche tebal dipanggang renyah dengan lelehan cokelat dan keju cheddar.',
                        'price' => 20000,
                    ],
                    [
                        'name' => 'Nasi Goreng Rempah CoffeBar',
                        'description' => 'Nasi goreng racikan rempah khas Nusantara dengan telur mata sapi dan kerupuk.',
                        'price' => 35000,
                    ],
                ],
            ],
            [
                'name' => 'Snack',
                'slug' => 'snack',
                'description' => 'Camilan renyah dan gurih pas untuk teman ngobrol santai.',
                'icon' => 'cookie',
                'sort_order' => 4,
                'products' => [
                    [
                        'name' => 'French Fries Herb',
                        'description' => 'Kentang goreng renyah berbumbu rosemary dan taburan garam laut alami.',
                        'price' => 20000,
                    ],
                    [
                        'name' => 'Singkong Krispi Sambal Roa',
                        'description' => 'Singkong goreng merekah empuk disajikan dengan sambal roa pedas gurih.',
                        'price' => 18000,
                    ],
                    [
                        'name' => 'Churros Cinnamon Sugar',
                        'description' => 'Donat stik Spanyol renyah berbalur gula kayu manis dan cocolan saus cokelat.',
                        'price' => 22000,
                    ],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $products = $catData['products'];
            unset($catData['products']);

            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );

            foreach ($products as $idx => $prodData) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($prodData['name'])],
                    [
                        'category_id' => $category->id,
                        'name' => $prodData['name'],
                        'description' => $prodData['description'],
                        'price' => $prodData['price'],
                        'is_available' => true,
                        'sort_order' => $idx + 1,
                    ]
                );
            }
        }

        // Global Product Options (Size, Sugar, Ice, Toppings, Bases)
        $options = [
            // Size (18 oz, 12 oz, 8 oz)
            ['type' => 'size', 'name' => '18 oz (Large)', 'price' => 8000, 'sort_order' => 1],
            ['type' => 'size', 'name' => '12 oz (Regular)', 'price' => 4000, 'sort_order' => 2],
            ['type' => 'size', 'name' => '8 oz (Small)', 'price' => 0, 'sort_order' => 3],

            // Base for Custom Menu
            ['type' => 'base', 'name' => 'Espresso Blend', 'price' => 16000, 'sort_order' => 1],
            ['type' => 'base', 'name' => 'Cold Brew Concentrate', 'price' => 18000, 'sort_order' => 2],
            ['type' => 'base', 'name' => 'Matcha Pure', 'price' => 18000, 'sort_order' => 3],
            ['type' => 'base', 'name' => 'Earl Grey Tea', 'price' => 15000, 'sort_order' => 4],
            ['type' => 'base', 'name' => 'Fresh Steamed Milk', 'price' => 14000, 'sort_order' => 5],

            // Sugar
            ['type' => 'sugar', 'name' => 'Tanpa Gula (0%)', 'price' => 0, 'sort_order' => 1],
            ['type' => 'sugar', 'name' => 'Sedikit Manis (50%)', 'price' => 0, 'sort_order' => 2],
            ['type' => 'sugar', 'name' => 'Normal (100%)', 'price' => 0, 'sort_order' => 3],

            // Ice / Temperature
            ['type' => 'ice', 'name' => 'Dingin (Es Batu)', 'price' => 0, 'sort_order' => 1],
            ['type' => 'ice', 'name' => 'Hangat / Panas', 'price' => 0, 'sort_order' => 2],

            // Topping / Addon
            ['type' => 'topping', 'name' => 'Boba Brown Sugar', 'price' => 5000, 'sort_order' => 1],
            ['type' => 'topping', 'name' => 'Grass Jelly', 'price' => 4000, 'sort_order' => 2],
            ['type' => 'topping', 'name' => 'Whipped Cream', 'price' => 5000, 'sort_order' => 3],
            ['type' => 'topping', 'name' => 'Caramel Drizzle', 'price' => 3000, 'sort_order' => 4],
            ['type' => 'topping', 'name' => 'Extra Espresso Shot', 'price' => 6000, 'sort_order' => 5],
            ['type' => 'topping', 'name' => 'Oat Milk Sub', 'price' => 7000, 'sort_order' => 6],
        ];

        foreach ($options as $opt) {
            ProductOption::updateOrCreate(
                ['type' => $opt['type'], 'name' => $opt['name'], 'product_id' => null],
                [
                    'price' => $opt['price'],
                    'is_available' => true,
                    'sort_order' => $opt['sort_order'],
                ]
            );
        }
    }
}
