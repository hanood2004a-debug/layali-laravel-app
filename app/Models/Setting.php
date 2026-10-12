<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];

    public const DEFAULTS = [
        'about'   => "ليالي مطعم ومقهى يجمع بين أصالة المطبخ الشرقي ولمسات المطبخ المتوسطي في أجواء هادئة وأنيقة.\nنُحضّر أطباقنا يومياً بمكوّنات طازجة وعلى نار هادئة، من المقبلات والمشاوي إلى الحلويات والمشروبات، لنقدّم لضيوفنا مائدة تليق بأجمل الأمسيات.",
        'mission' => "هدفنا أن نقدّم تجربة طعام راقية بنكهات أصيلة وخدمة دافئة، وأن تكون كل زيارة إلى ليالي أمسية تستحق أن تُذكر.",
        'loyalty_enabled'       => '1',
        'loyalty_rate'          => '1',   // نقطة لكل 1 ₪
        'loyalty_redeem_points' => '200', // عدد النقاط للمكافأة الواحدة
        'loyalty_redeem_value'  => '10',  // قيمة الخصم بالشيكل
    ];

    public static function get(string $name): string
    {
        $v = static::where('name', $name)->value('value');

        // القيمة '0' صالحة (مثلاً إيقاف البرنامج) فلا نعتمد على ?: هنا
        return ($v !== null && $v !== '') ? $v : (self::DEFAULTS[$name] ?? '');
    }

    public static function put(string $name, ?string $value): void
    {
        static::updateOrCreate(['name' => $name], ['value' => $value]);
    }
}
