<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\User;
use App\Support\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // حساب الإدارة الوحيد (لا يوجد تسجيل للزبائن). غيّر البيانات من .env قبل db:seed
        $admin = User::firstOrNew(['email' => env('ADMIN_EMAIL', 'admin@layali.com')]);
        if (!$admin->exists) {
            $admin->name = 'المدير';
            $admin->password = Hash::make(env('ADMIN_PASSWORD', 'Layali@12345'));
            $admin->email_verified_at = now();
            $admin->save();
        }

        if (Category::count() === 0) {
            $i = 0;
            foreach (Menu::categories() as $catName => $items) {
                $cat = Category::create(['name' => $catName, 'sort' => ++$i]);
                foreach ($items as $n => $it) {
                    MenuItem::create([
                        'category_id' => $cat->id, 'name' => $it['name'], 'price' => $it['price'],
                        'description' => $it['desc'], 'details' => $it['details'], 'ingredients' => $it['ingredients'],
                        'image' => $it['img'], 'sort' => $n + 1,
                    ]);
                }
            }
        }
    }
}
