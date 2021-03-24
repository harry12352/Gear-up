<?php

use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = ['Small', 'Large', 'Medium', 'ExtraLarge', 'ExtraSmall'];
        foreach ($sizes as $size) {
            \App\Models\Size::create([
                'name' => $size
            ]);
        }
    }
}
