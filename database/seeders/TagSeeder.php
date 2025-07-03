<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'iPhone',
            'Samsung',
            'Android',
            'iOS',
            'MacBook',
            'Laptop Gaming',
            'Đồng hồ thông minh',
            'Tai nghe',
            'Sạc dự phòng',
            'Ốp lưng điện thoại',
            'Cáp sạc',
            'Phụ kiện công nghệ',
            'Điện thoại giá rẻ',
            'Laptop văn phòng',
            'Apple Watch',
            'Tablet',
            'Máy tính bảng',
            'Chuột không dây',
            'Bàn phím cơ',
            'Thiết bị đeo tay',
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
            ]);
        }
    }
}
