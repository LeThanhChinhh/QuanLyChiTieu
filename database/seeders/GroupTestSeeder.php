<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class GroupTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 2 test users
        $user1 = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'vana@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Trần Thị B',
            'email' => 'thib@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create basic categories if not exist
        $categories = [
            ['name' => 'Ăn uống', 'type' => 'expense', 'icon' => 'ri-restaurant-line', 'color' => '#EF4444'],
            ['name' => 'Đi chơi', 'type' => 'expense', 'icon' => 'ri-plane-line', 'color' => '#3B82F6'],
            ['name' => 'Tiền nhà', 'type' => 'expense', 'icon' => 'ri-home-line', 'color' => '#10B981'],
            ['name' => 'Mua sắm', 'type' => 'expense', 'icon' => 'ri-shopping-cart-line', 'color' => '#F59E0B'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name'], 'user_id' => null],
                $categoryData
            );
        }

        $this->command->info('✅ Created 2 test users:');
        $this->command->info("   📧 {$user1->email} / password");
        $this->command->info("   📧 {$user2->email} / password");
        $this->command->info('✅ Created 4 default categories');
        $this->command->info('');
        $this->command->info('🚀 You can now login and start testing!');
    }
}
