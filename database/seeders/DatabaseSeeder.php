<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Create Regular Users for testing
        User::factory()->create([
            'name' => 'Nguyễn Văn A',
            'email' => 'vana@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Trần Thị B',
            'email' => 'thib@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Lê Văn C',
            'email' => 'levanc@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('✅ Created 5 test users:');
        $this->command->info('   👤 Admin: admin@example.com / password (role: admin)');
        $this->command->info('   👤 User: vana@example.com / password');
        $this->command->info('   👤 User: thib@example.com / password');
        $this->command->info('   👤 User: levanc@example.com / password');
        $this->command->info('   👤 User: test@example.com / password');
    }
}
