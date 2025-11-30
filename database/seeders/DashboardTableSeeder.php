<?php

namespace Database\Seeders;

use App\Models\Dashboard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DashboardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboards = [
            [
                'title' => 'Admin Dashboard',
                'description' => 'Main administrative dashboard with system overview',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'title' => 'Analytics Dashboard',
                'description' => 'Analytics and reporting dashboard',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'title' => 'User Management',
                'description' => 'Manage system users and permissions',
                'is_active' => true,
                'position' => 3,
            ],
        ];

        foreach ($dashboards as $dashboard) {
            $dashboard['slug'] = Str::slug($dashboard['title']);
            Dashboard::create($dashboard);
        }
    }
}
