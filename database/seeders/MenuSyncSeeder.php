<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Support\Menu;
use Illuminate\Database\Seeder;

/**
 * يحدّث المنيو الموجود بأسماء وصور وشرح الأصناف الجديدة من Menu.php
 * بدون مسح الطلبات أو التقييمات أو المصاريف:  php artisan db:seed --class=MenuSyncSeeder
 */
class MenuSyncSeeder extends Seeder
{
    // الاسم القديم => الاسم الجديد (للأصناف التي تغيّر اسمها لتطابق الصور)
    private const RENAMED = [
        'تبولة الكينوا بالرمان' => 'كينوا بالجزر والبقدونس',
        'لبنة بالثوم والزيتون' => 'فتّة الرمان والبصل المكرمل',
        'فطر محشو بالأعشاب' => 'فطر محشو بالفلفل',
        'جمبري بالزبدة والثوم' => 'جمبري بصلصة الثوم الكريمية',
        'حلومي مشوي بالعسل' => 'توست الجبنة والثوم',
        'فتّوش بالسماق' => 'سلطة الجبنة والزيتون',
        'سلطة الروكا والجوز' => 'سلطة الشمندر والجوز',
        'شوربة الفطر الكريمية' => 'شوربة الدجاج الكريمية',
        'لحم بالبرقوق واللوز' => 'شيشبرك باللبن',
        'فيتوتشيني بالدجاج والفطر' => 'فيتوتشيني بالدجاج الكريمي',
        'معكرونة الجمبري' => 'معكرونة الجمبري بالكريمة',
        'ريزوتو الفطر والبارميزان' => 'ريزوتو الدجاج والفطر',
        'قهوة عربية بالهيل' => 'قهوة تركية',
        'إسبريسو مزدوج' => 'قهوة أمريكانو',
    ];

    public function run(): void
    {
        $oldByNew = array_flip(self::RENAMED);
        $updated = $created = 0;

        foreach (Menu::categories() as $catName => $items) {
            $cat = Category::firstOrCreate(['name' => $catName], ['sort' => (int) Category::max('sort') + 1]);

            foreach ($items as $n => $it) {
                $item = MenuItem::where('name', $it['name'])->first();
                $renamed = false;

                if (!$item && isset($oldByNew[$it['name']])) {
                    $item = MenuItem::where('name', $oldByNew[$it['name']])->first();
                    $renamed = (bool) $item;
                }

                if ($item) {
                    $data = [
                        'name'        => $it['name'],
                        'description' => $it['desc'],
                        'details'     => $it['details'],
                        'ingredients' => $it['ingredients'],
                    ];
                    if ($it['img']) {
                        $data['image'] = $it['img'];
                    }
                    if ($renamed) {
                        $data['price'] = $it['price']; // صنف مختلف فسعره الجديد
                    }
                    $item->update($data);
                    $updated++;
                } else {
                    MenuItem::create([
                        'category_id' => $cat->id, 'name' => $it['name'], 'price' => $it['price'],
                        'description' => $it['desc'], 'details' => $it['details'], 'ingredients' => $it['ingredients'],
                        'image' => $it['img'], 'sort' => $n + 1,
                    ]);
                    $created++;
                }
            }
        }

        $this->command?->info("تم تحديث {$updated} صنفاً وإضافة {$created} صنفاً جديداً.");
    }
}
