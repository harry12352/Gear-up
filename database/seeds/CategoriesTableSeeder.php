<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ["Beach ", "Boating", "Boxing", "Climb", "Cycling ", "Bike", "Bike Accessories",
        "Helmet", "Apparel ", "Equestrian"];
        foreach ($categories as $category) {
            $categoryUrl = Str::slug($category, '-');
            Category::updateOrCreate(['slug' => $categoryUrl], ['name' => $category, 'slug' => $categoryUrl]);
        }
    }
}
