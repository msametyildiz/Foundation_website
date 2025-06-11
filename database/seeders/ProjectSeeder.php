<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'title' => 'Eğitim Bursu Projesi',
                'slug' => 'egitim-bursu-projesi',
                'short_description' => 'Maddi durumu iyi olmayan öğrencilere eğitim bursu sağlıyoruz.',
                'description' => 'Bu proje kapsamında her yıl 50 öğrenciye burs verilerek eğitim hayatlarına destek olmaktayız. Burs miktarı öğrencinin durumuna göre belirlenmekte ve başvuru süreci her yıl Eylül ayında başlamaktadır.',
                'category' => 'education',
                'status' => 'active',
                'target_amount' => 100000.00,
                'collected_amount' => 75000.00,
                'beneficiaries' => 50,
                'is_featured' => 1,
                'sort_order' => 1,
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->addMonths(6),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Gıda Yardımı Kampanyası',
                'slug' => 'gida-yardimi-kampanyasi',
                'short_description' => 'İhtiyaç sahibi ailelere gıda kolileri dağıtıyoruz.',
                'description' => 'Ramazan ayında ve kış aylarında düzenlediğimiz bu kampanya ile 200 aileye düzenli olarak gıda yardımı ulaştırıyoruz. Her koliyi özenle hazırlayarak ailelerin temel gıda ihtiyaçlarını karşılamaya çalışıyoruz.',
                'category' => 'social',
                'status' => 'active',
                'target_amount' => 50000.00,
                'collected_amount' => 32000.00,
                'beneficiaries' => 200,
                'is_featured' => 1,
                'sort_order' => 2,
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(9),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Kışlık Giysi Yardımı',
                'slug' => 'kislik-giysi-yardimi',
                'short_description' => 'Soğuk kış aylarında ihtiyaç sahibi çocuk ve yetişkinlere kışlık giysi desteği sağlıyoruz.',
                'description' => 'Kış mevsimi öncesinde toplanan bağışlar ile mont, kazak, bot gibi kışlık giysileri ihtiyaç sahibi ailelerle buluşturuyoruz.',
                'category' => 'social',
                'status' => 'completed',
                'target_amount' => 30000.00,
                'collected_amount' => 30000.00,
                'beneficiaries' => 150,
                'is_featured' => 0,
                'sort_order' => 3,
                'start_date' => Carbon::now()->subMonths(8),
                'end_date' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Yaşlı Bakım Projesi',
                'slug' => 'yasli-bakim-projesi',
                'short_description' => 'Yalnız yaşayan yaşlılarımıza evde bakım hizmeti ve sosyal destek sağlıyoruz.',
                'description' => 'Gönüllülerimiz düzenli olarak yaşlı vatandaşlarımızı ziyaret ederek hem sağlık kontrollerini takip ediyor hem de sosyal ihtiyaçlarını karşılamaya çalışıyoruz.',
                'category' => 'health',
                'status' => 'active',
                'target_amount' => 80000.00,
                'collected_amount' => 45000.00,
                'beneficiaries' => 75,
                'is_featured' => 1,
                'sort_order' => 4,
                'start_date' => Carbon::now()->subMonths(4),
                'end_date' => Carbon::now()->addYear(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Kütüphane Kurma Projesi',
                'slug' => 'kutuphane-kurma-projesi',
                'short_description' => 'Kırsal bölgelerde çocukların kitaba ulaşabilmesi için mini kütüphaneler kuruyoruz.',
                'description' => 'Eğitime verdiğimiz önemin bir göstergesi olarak köy okullarında ve mahalle merkezlerinde mini kütüphaneler kurarak çocukların kitap okuma alışkanlığı kazanmalarını destekliyoruz.',
                'category' => 'education',
                'status' => 'planning',
                'target_amount' => 40000.00,
                'collected_amount' => 15000.00,
                'beneficiaries' => 300,
                'is_featured' => 0,
                'sort_order' => 5,
                'start_date' => Carbon::now()->addMonth(),
                'end_date' => Carbon::now()->addYear(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('projects')->insert($projects);
    }
}
