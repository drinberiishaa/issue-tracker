<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'bug', 'color' => '#EF4444'],
            ['name' => 'feature', 'color' => '#3B82F6'],
            ['name' => 'enhancement', 'color' => '#8B5CF6'],
            ['name' => 'documentation', 'color' => '#10B981'],
            ['name' => 'urgent', 'color' => '#F97316'],
            ['name' => 'backend', 'color' => '#6366F1'],
            ['name' => 'frontend', 'color' => '#EC4899'],
            ['name' => 'wontfix', 'color' => '#6B7280'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag['name']], $tag);
        }
    }
}
