<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\UiTranslation;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        // Create languages
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'bn',
                'name' => 'Bengali',
                'native_name' => 'বাংলা',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'native_name' => 'हिंदी',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'code'  => 'fa',
                'name'  => 'Persian',
                'native_name' => 'فارسی',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4
            ],
            [
                'code'  => 'ar',
                'name'  => 'Arabic',
                'native_name' => 'العربية',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 5
            ],
            [
                'code'  => 'ur',
                'name'  => 'Urdu',
                'native_name' => 'اردو',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 6
            ],
            [
                'code'  => 'zh',
                'name'  => 'Chinese',
                'native_name' => '中文',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 7
            ],
            [
                'code'  => 'ko',
                'name'  => 'Korean',
                'native_name' => '한국어',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 8
            ],
            [
                'code'  => 'ja',
                'name'  => 'Japanese',
                'native_name' => '日本語',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 9
            ],
            [
                'code'  => 'de',
                'name'  => 'German',
                'native_name' => 'Deutsch',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 10
            ]
        ];

        foreach ($languages as $languageData) {
            Language::create($languageData);
        }

        // Create initial translations
        $this->createInitialTranslations();
    }

    private function createInitialTranslations(): void
    {
        $english = Language::where('code', 'en')->first();
        $bengali = Language::where('code', 'bn')->first();
        $hindi = Language::where('code', 'hi')->first();
        $persian = Language::where('code', 'fa')->first();
        $arabic = Language::where('code', 'ar')->first();
        $urdu = Language::where('code', 'ur')->first();
        $chinese = Language::where('code', 'zh')->first();
        $korean = Language::where('code', 'ko')->first();
        $japanese = Language::where('code', 'ja')->first();
        $german = Language::where('code', 'de')->first();

        $translations = [
            'welcome' => ['en' => 'Welcome', 'bn' => 'স্বাগতম','hi' => 'स्वागत है', 'fa' => 'خوش آمدید', 'ar' => 'مرحبا', 'ur' => 'سلام', 'zh' => '欢迎', 'ko' => '환영합니다', 'ja' => 'ようこそ', 'de' => 'Willkommen'],
            'dashboard' => ['en' => 'Dashboard', 'bn' => 'ড্যাশবোর্ড'],
            'profile' => ['en' => 'Profile', 'bn' => 'প্রোফাইল','hi' => 'प्रोफ़ाइल','fa' => 'پروفایل', 'ar' => 'الملف الشخصي', 'ur' => 'پروفائل', 'zh' => '个人资料', 'ko' => '프로필', 'ja' => 'プロフィール', 'de' => 'Profil'],
            'user' => ['en' => 'Users', 'bn' => 'ব্যবহারকারী'],
            'username' => ['en' => 'Username', 'bn' => 'ব্যবহারকারীর নাম'],
            'search' => ['en' => 'Search', 'bn' => 'অনুসন্ধান', 'hi' => 'खोज', 'fa' => 'جستجو', 'ar' => 'بحث', 'ur' => 'تلاش', 'zh' => '搜索', 'ko' => '검색', 'ja' => '検索', 'de' => 'Suche'],
            'name' => ['en' => 'Name', 'bn' => 'নাম', 'hi' => 'नाम', 'fa' => 'نام', 'ar' => 'الاسم', 'ur' => 'نام', 'zh' => '名字', 'ko' => '이름', 'ja' => '名前', 'de' => 'Name'],
            'email' => ['en' => 'Email', 'bn' => 'ইমেইল'],
            'action' => ['en' => 'Action', 'bn' => 'অ্যাকশন', 'hi' => 'कार्य', 'fa' => 'عملیات', 'ar' => 'إجراء', 'ur' => 'عمل', 'zh' => '行动', 'ko' => '행동', 'ja' => '行動', 'de' => 'Aktion'],
            'phone' => ['en' => 'Phone', 'bn' => 'ফোন', 'hi' => 'फोन', 'fa' => 'تلفن', 'ar' => 'هاتف', 'ur' => 'تلفن', 'zh' => '电话', 'ko' => '전화번호', 'ja' => '電話', 'de' => 'Telefon'],
            'role' => ['en' => 'Role', 'bn' => 'ভূমিকা', 'hi' => 'रोल', 'fa' => 'نقش', 'ar' => 'دور', 'ur' => 'رول', 'zh' => '角色', 'ko' => '역할', 'ja' => '役割', 'de' => 'Rolle'],
            'overview' => ['en' => 'Overview', 'bn' => 'মূলত', 'hi' => 'अवलोकन', 'fa' => 'بررسی کلی', 'ar' => 'نظرة عامة', 'ur' => 'نظرہ', 'zh' => '总览', 'ko' => '개요', 'ja' => '概要', 'de' => 'Überblick'],
            'decline' => ['en' => 'Decline', 'bn' => 'প্রত্যাখ্যান', 'hi' => 'अस्वीकार', 'fa' => 'رد', 'ar' => 'رفض', 'ur' => 'رفض', 'zh' => '拒绝', 'ko' => '거부', 'ja' => '断る', 'de' => 'Ablehnen'],
            'update' => ['en' => 'Update', 'bn' => 'আপডেট করুন', 'hi' => 'अपडेट', 'fa' => 'اپدیت', 'ar' => 'تحديث', 'ur' => 'اپ ڈیٹ', 'zh' => '更新', 'ko' => '업데이트', 'ja' => '更新', 'de' => 'Aktualisieren'],
            'submit' => ['en' => 'Submit', 'bn' => 'সাবমিট করুন', 'hi' => 'सबमिट', 'fa' => 'ارسال', 'ar' => 'تقديم', 'ur' => 'جمع کریں', 'zh' => '提交', 'ko' => '제출', 'ja' => '提出', 'de' => 'Einreichen'],
            'image' => ['en' => 'Image', 'bn' => 'ছবি', 'hi' => 'छवि', 'fa' => 'عکس', 'ar' => 'صورة', 'ur' => 'تصویر', 'zh' => '图像', 'ko' => '이미지', 'ja' => '画像', 'de' => 'Bild'],
            'status' => ['en' => 'Status', 'bn' => 'স্টেটাস', 'hi' => 'स्टेटस', 'fa' => 'وضعیت', 'ar' => 'حالة', 'ur' => 'وضوح', 'zh' => '状态', 'ko' => '상태', 'ja' => 'ステータス', 'de' => 'Status'],
            'logo' => ['en' => 'Logo', 'bn' => 'লোগো', 'hi' => 'लॉगो', 'fa' => 'لوگو', 'ar' => 'لوجو', 'ur' => 'لوگو', 'zh' => '标志', 'ko' => '로고', 'ja' => 'ロゴ', 'de' => 'Logo'],
            'mobile' => ['en' => 'Mobile', 'bn' => 'মোবাইল', 'hi' => 'मोबाइल', 'fa' => 'موبایل', 'ar' => 'موبايل', 'ur' => 'موبائل', 'zh' => '移动', 'ko' => '모바일', 'ja' => 'モバイル', 'de' => 'Mobil'],
            'nid' => ['en' => 'Nid', 'bn' => 'এনআইডি', 'hi' => 'Nid', 'fa' => 'ند', 'ar' => 'Nid', 'ur' => 'Nid', 'zh' => 'Nid', 'ko' => 'Nid', 'ja' => 'Nid', 'de' => 'Nid'],
            'home' => ['en' => 'Home', 'bn' => 'হোম', 'hi' => 'होम', 'fa' => 'خانه', 'ar' => 'الصفحة الرئيسية', 'ur' => 'ہوم', 'zh' => '主页', 'ko' => '홈', 'ja' => 'ホーム', 'de' => 'Zuhause'],
            'signin' => ['en' => 'Signin', 'bn' => 'সাইন ইন'],
            'signup' => ['en' => 'Signup', 'bn' => 'সাইন আপ'],
            'signout' => ['en' => 'Signout', 'bn' => 'সাইন আউট'],
            'password' => ['en' => 'Password', 'bn' => 'পাসওয়ার্ড'],
            'language' => ['en' => 'Language', 'bn' => 'ভাষা', 'hi' => 'भाषा', 'fa' => 'زبان', 'ar' => 'لغة', 'ur' => 'زبان', 'zh' => '语言', 'ko' => '언어', 'ja' => '言語', 'de' => 'Sprache'],
            'change_password' => ['en' => 'Change Password', 'bn' => 'পাসওয়ার্ড পরিবর্তন করুন'],
            'settings' => ['en' => 'Settings', 'bn' => 'সেটিং','hi' => 'सेटिंग्स','fa' => 'تنظیمات','ar' => 'إعدادات','ur' => 'سیٹینگز','zh' => '设置','ko' => '설정','ja' => '設定','de' => 'Einstellungen'],
        ];

        foreach ($translations as $key => $values) {
            // English translation
            UiTranslation::create([
                'language_id' => $english->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['en'],
            ]);

            // Bengali translation
            UiTranslation::create([
                'language_id' => $bengali->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['bn'],
            ]);

            // Hindi translation
            UiTranslation::create([
                'language_id' => $hindi->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['hi'] ?? $values['en'],
            ]);

            // Persian translation
            UiTranslation::create([
                'language_id' => $persian->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['fa'] ?? $values['en'],
            ]);

            // Arabic translation
            UiTranslation::create([
                'language_id' => $arabic->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['ar'] ?? $values['en'],
            ]);

            // Urdu translation
            UiTranslation::create([
                'language_id' => $urdu->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['ur'] ?? $values['en'],
            ]);

            // Chinese translation
            UiTranslation::create([
                'language_id' => $chinese->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['zh'] ?? $values['en'],
            ]);

            // Korean translation
            UiTranslation::create([
                'language_id' => $korean->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['ko'] ?? $values['en'],
            ]);

            // Japanese translation
            UiTranslation::create([
                'language_id' => $japanese->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['ja'] ?? $values['en'],
            ]);

            // German translation
            UiTranslation::create([
                'language_id' => $german->id,
                'group' => 'ui',
                'key' => $key,
                'value' => $values['de'] ?? $values['en'],
            ]);
        }
    }
}