<?php

namespace Database\Factories;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Catalog>
 */
class CatalogFactory extends Factory
{
    protected $model = Catalog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tieu_de' => fake()->sentence(),
            'anh_dai_dien' => 'catalog/images/fake_image.jpg',
            'file' => 'catalog/files/fake_file.pdf',
        ];
    }
}
