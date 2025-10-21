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
                'code'          => 'en',
                'name'          => 'English',
                'native_name'   => 'English',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => true,
                'sort_order'    => 1,
            ],
            [
                'code'          => 'bn',
                'name'          => 'Bengali',
                'native_name'   => 'বাংলা',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 2,
            ],
            [
                'code'          => 'hi',
                'name'          => 'Hindi',
                'native_name'   => 'हिंदी',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 3,
            ],
            [
                'code'          => 'fa',
                'name'          => 'Persian',
                'native_name'   => 'فارسی',
                'direction'     => 'rtl',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 4
            ],
            [
                'code'          => 'ar',
                'name'          => 'Arabic',
                'native_name'    => 'العربية',
                'direction'     => 'rtl',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 5
            ],
            [
                'code'          => 'ur',
                'name'          => 'Urdu',
                'native_name'   => 'اردو',
                'direction'     => 'rtl',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 6
            ],
            [
                'code'          => 'zh',
                'name'          => 'Chinese',
                'native_name'   => '中文',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 7
            ],
            [
                'code'          => 'ko',
                'name'          => 'Korean',
                'native_name'   => '한국어',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 8
            ],
            [
                'code'          => 'ja',
                'name'          => 'Japanese',
                'native_name'   => '日本語',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 9
            ],
            [
                'code'          => 'de',
                'name'          => 'German',
                'native_name'   => 'Deutsch',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 10
            ],
            [
                'code'          => 'es',
                'name'          => 'Spanish',
                'native_name'   => 'Español',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 11
            ],
            [
                'code'          => 'fr',
                'name'          => 'French',
                'native_name'   => 'Français',
                'direction'     => 'ltr',
                'is_active'     => true,
                'is_default'    => false,
                'sort_order'    => 12
            ]
        ];

        foreach ($languages as $languageData) {
            Language::create($languageData);
        }

        $this->createInitialTranslations();
    }

    private function createInitialTranslations(): void
    {
        $translations = [
            'welcome' => ['en' => 'Welcome', 'bn' => 'স্বাগতম','hi' => 'स्वागत है', 'fa' => 'خوش آمدید', 'ar' => 'مرحبا', 'ur' => 'سلام', 'zh' => '欢迎', 'ko' => '환영합니다', 'ja' => 'ようこそ', 'de' => 'Willkommen' , 'es' => 'Bienvenido', 'fr' => 'Bienvenue'],
            'dashboard' => ['en' => 'Dashboard', 'bn' => 'ড্যাশবোর্ড','hi' => 'डैशबोर्ड','fa' => 'داشبورد','ar' => 'لوحة القيادة','ur' => 'ڈیش بورڈ','zh' => '仪表板','ko' => '대시포드','ja' => 'ダッシュボード','de' => 'Instrumententafel', 'es' => 'Tablero', 'fr' => 'Tableau de bord'],
            'profile' => ['en' => 'Profile', 'bn' => 'প্রোফাইল','hi' => 'प्रोफ़ाइल','fa' => 'پروفایل', 'ar' => 'الملف الشخصي', 'ur' => 'پروفائل', 'zh' => '个人资料', 'ko' => '프로필', 'ja' => 'プロフィール', 'de' => 'Profil', 'es' => 'Perfil', 'fr' => 'Profil'],
            'user' => ['en' => 'Users', 'bn' => 'ব্যবহারকারী', 'hi' => 'उपयोगकर्ता', 'fa' => 'کاربران', 'ar' => 'المستخدمون', 'ur' => 'صارفین', 'zh' => '用户', 'ko' => '유저', 'ja' => 'ユーザー', 'de' => 'Benutzer', 'es' => 'Usuarios', 'fr' => 'Utilisateurs'],
            'username' => ['en' => 'Username', 'bn' => 'ব্যবহারকারীর নাম', 'hi' => 'उपयोगकर्ता नाम', 'fa' => 'نام کاربری', 'ar' => 'اسم المستخدم', 'ur' => 'صارف کا نام', 'zh' => '用户名', 'ko' => '유저네임', 'ja' => 'ユーザー名', 'de' => 'Benutzername', 'es' => 'Nombre de usuario', 'fr' => 'Nom d\'utilisateur'],
            'search' => ['en' => 'Search', 'bn' => 'অনুসন্ধান', 'hi' => 'खोज', 'fa' => 'جستجو', 'ar' => 'بحث', 'ur' => 'تلاش', 'zh' => '搜索', 'ko' => '검색', 'ja' => '検索', 'de' => 'Suche', 'es' => 'Buscar', 'fr' => 'Rechercher'],
            'name' => ['en' => 'Name', 'bn' => 'নাম', 'hi' => 'नाम', 'fa' => 'نام', 'ar' => 'الاسم', 'ur' => 'نام', 'zh' => '名字', 'ko' => '이름', 'ja' => '名前', 'de' => 'Name', 'es' => 'Nombre', 'fr' => 'Nom'],
            'email' => ['en' => 'Email', 'bn' => 'ইমেইল', 'hi' => 'ईमेल', 'fa' => 'ایمیل', 'ar' => 'البريد الإلكتروني', 'ur' => 'ای میل', 'zh' => '电子邮件', 'ko' => '이밀', 'ja' => 'メール', 'de' => 'E-Mail', 'es' => 'Correo electrónico', 'fr' => 'Email'],
            'action' => ['en' => 'Action', 'bn' => 'অ্যাকশন', 'hi' => 'कार्य', 'fa' => 'عملیات', 'ar' => 'إجراء', 'ur' => 'عمل', 'zh' => '行动', 'ko' => '행동', 'ja' => '行動', 'de' => 'Aktion', 'es' => 'Acción', 'fr' => 'Action'],
            'phone' => ['en' => 'Phone', 'bn' => 'ফোন', 'hi' => 'फोन', 'fa' => 'تلفن', 'ar' => 'هاتف', 'ur' => 'تلفن', 'zh' => '电话', 'ko' => '전화번호', 'ja' => '電話', 'de' => 'Telefon', 'es' => 'Teléfono', 'fr' => 'Téléphone'],
            'role' => ['en' => 'Role', 'bn' => 'ভূমিকা', 'hi' => 'रोल', 'fa' => 'نقش', 'ar' => 'دور', 'ur' => 'رول', 'zh' => '角色', 'ko' => '역할', 'ja' => '役割', 'de' => 'Rolle', 'es' => 'Rol', 'fr' => 'Rôle'],
            'overview' => ['en' => 'Overview', 'bn' => 'মূলত', 'hi' => 'अवलोकन', 'fa' => 'بررسی کلی', 'ar' => 'نظرة عامة', 'ur' => 'نظرہ', 'zh' => '总览', 'ko' => '개요', 'ja' => '概要', 'de' => 'Überblick', 'es' => 'Resumen', 'fr' => 'Aperçu'],
            'decline' => ['en' => 'Decline', 'bn' => 'প্রত্যাখ্যান', 'hi' => 'अस्वीकार', 'fa' => 'رد', 'ar' => 'رفض', 'ur' => 'رفض', 'zh' => '拒绝', 'ko' => '거부', 'ja' => '断る', 'de' => 'Ablehnen', 'es' => 'Rechazar', 'fr' => 'Refuser'],
            'update' => ['en' => 'Update', 'bn' => 'আপডেট করুন', 'hi' => 'अपडेट', 'fa' => 'اپدیت', 'ar' => 'تحديث', 'ur' => 'اپ ڈیٹ', 'zh' => '更新', 'ko' => '업데이트', 'ja' => '更新', 'de' => 'Aktualisieren', 'es' => 'Actualizar', 'fr' => 'Mettre à jour'],
            'submit' => ['en' => 'Submit', 'bn' => 'সাবমিট করুন', 'hi' => 'सबमिट', 'fa' => 'ارسال', 'ar' => 'تقديم', 'ur' => 'جمع کریں', 'zh' => '提交', 'ko' => '제출', 'ja' => '提出', 'de' => 'Einreichen', 'es' => 'Enviar', 'fr' => 'Soumettre'],
            'image' => ['en' => 'Image', 'bn' => 'ছবি', 'hi' => 'छवि', 'fa' => 'عکس', 'ar' => 'صورة', 'ur' => 'تصویر', 'zh' => '图像', 'ko' => '이미지', 'ja' => '画像', 'de' => 'Bild', 'es' => 'Imagen', 'fr' => 'Image'],
            'status' => ['en' => 'Status', 'bn' => 'স্টেটাস', 'hi' => 'स्टेटस', 'fa' => 'وضعیت', 'ar' => 'حالة', 'ur' => 'وضوح', 'zh' => '状态', 'ko' => '상태', 'ja' => 'ステータス', 'de' => 'Status', 'es' => 'Estado', 'fr' => 'Statut'],
            'logo' => ['en' => 'Logo', 'bn' => 'লোগো', 'hi' => 'लॉगो', 'fa' => 'لوگو', 'ar' => 'لوجو', 'ur' => 'لوگو', 'zh' => '标志', 'ko' => '로고', 'ja' => 'ロゴ', 'de' => 'Logo', 'es' => 'Logo', 'fr' => 'Logo'],
            'mobile' => ['en' => 'Mobile', 'bn' => 'মোবাইল', 'hi' => 'मोबाइल', 'fa' => 'موبایل', 'ar' => 'موبايل', 'ur' => 'موبائل', 'zh' => '移动', 'ko' => '모바일', 'ja' => 'モバイル', 'de' => 'Mobil', 'es' => 'Móvil', 'fr' => 'Mobile'],
            'nid' => ['en' => 'Nid', 'bn' => 'এনআইডি', 'hi' => 'Nid', 'fa' => 'ند', 'ar' => 'Nid', 'ur' => 'Nid', 'zh' => 'Nid', 'ko' => 'Nid', 'ja' => 'Nid', 'de' => 'Nid', 'es' => 'Nid', 'fr' => 'Nid'],
            'home' => ['en' => 'Home', 'bn' => 'হোম', 'hi' => 'होम', 'fa' => 'خانه', 'ar' => 'الصفحة الرئيسية', 'ur' => 'ہوم', 'zh' => '主页', 'ko' => '홈', 'ja' => 'ホーム', 'de' => 'Zuhause', 'es' => 'Inicio', 'fr' => 'Accueil'],
            'signin' => ['en' => 'Signin', 'bn' => 'সাইন ইন', 'hi' => 'साइन इन', 'fa' => 'ورود', 'ar' => 'تسجيل الدخول', 'ur' => 'سائن ان', 'zh' => '登录', 'ko' => '사인', 'ja' => 'サインイン', 'de' => 'Anmelden', 'es' => 'Iniciar sesión', 'fr' => 'Connexion'],
            'signup' => ['en' => 'Signup', 'bn' => 'সাইন আপ', 'hi' => 'साइन अप', 'fa' => 'ثبت نام', 'ar' => 'اشتراك', 'ur' => 'سائن اپ', 'zh' => '注册', 'ko' => '사인업', 'ja' => 'サインアップ', 'de' => 'Anmelden'],
            'signout' => ['en' => 'Signout', 'bn' => 'সাইন আউট', 'hi' => 'साइन आउट', 'fa' => 'خروج', 'ar' => 'تسجيل الخروج', 'ur' => 'سائن آوٹ', 'zh' => '登出', 'ko' => '사인아웃', 'ja' => 'サインアウト', 'de' => 'Abmelden', 'es' => 'Cerrar sesión', 'fr' => 'Déconnexion'],
            'password' => ['en' => 'Password', 'bn' => 'পাসওয়ার্ড', 'hi' => 'पासवर्ड', 'fa' => 'رمز عبور', 'ar' => 'كلمة المرور', 'ur' => 'پاس ورڈ', 'zh' => '密码', 'ko' => '파쉐드', 'ja' => 'パスワード', 'de' => 'Passwort', 'es' => 'Contraseña', 'fr' => 'Mot de passe'],
            'language' => ['en' => 'Language', 'bn' => 'ভাষা', 'hi' => 'भाषा', 'fa' => 'زبان', 'ar' => 'لغة', 'ur' => 'زبان', 'zh' => '语言', 'ko' => '언어', 'ja' => '言語', 'de' => 'Sprache', 'es' => 'Idioma', 'fr' => 'Langue'],
            'change_password' => ['en' => 'Change Password', 'bn' => 'পাসওয়ার্ড পরিবর্তন করুন', 'hi' => 'पासवर्ड बदलें', 'fa' => 'تغییر رمز عبور', 'ar' => 'تغيير كلمة المرور', 'ur' => 'پاس ورڈ تبدیل کریں', 'zh' => '更改密码', 'ko' => '파쉐드 변경', 'ja' => 'パスワードを変更する', 'de' => 'Passwort ändern', 'es' => 'Cambiar la contraseña', 'fr' => 'Changer le mot de passe'],
            'settings' => ['en' => 'Settings', 'bn' => 'সেটিং','hi' => 'सेटिंग्स','fa' => 'تنظیمات','ar' => 'إعدادات','ur' => 'سیٹینگز','zh' => '设置','ko' => '설정','ja' => '設定','de' => 'Einstellungen', 'es' => 'Configuraciones', 'fr' => 'Paramètres'],
            'save' => ['en' => 'Save', 'bn' => 'সংরক্ষণ করুন','hi' => 'सहेजें','fa' => 'ذخیره','ar' => 'حفظ','ur' => 'محفوظ کریں','zh' => '保存','ko' => '직업','ja' => '保存','de' => 'Speichern', 'es' => 'Guardar', 'fr' => 'Sauvegarder'],
            'cancel' => ['en' => 'Cancel', 'bn' => 'বাতিল করুন','hi' => 'रद्द करें','fa' => 'لغو','ar' => 'إلغاء','ur' => 'منسوخ کریں','zh' => '取消','ko' => '작성 활업 분리','ja' => 'キャンセル','de' => 'Abbrechen', 'es' => 'Cancelar', 'fr' => 'Annuler'],
            'edit' => ['en' => 'Edit', 'bn' => 'সম্পাদনা করুন','hi' => 'संपादित करें','fa' => 'ویرایش','ar' => 'تعديل','ur' => 'ترمیم کریں','zh' => '编辑','ko' => '편집','ja' => '編集','de' => 'Bearbeiten', 'es' => 'Editar', 'fr' => 'Éditer'],
            'delete' => ['en' => 'Delete', 'bn' => 'মুছে ফেলুন','hi' => 'हटाएं','fa' => 'حذف','ar' => 'حذف','ur' => 'حذف کریں','zh' => '删除','ko' => '제거','ja' => '削除','de' => 'Löschen', 'es' => 'Eliminar', 'fr' => 'Supprimer'],
            'create' => ['en' => 'Create', 'bn' => 'তৈরি করুন','hi' => 'बनाएं','fa' => 'ایجاد','ar' => 'إنشاء','ur' => 'بنائیں','zh' => '创建','ko' => '생성','ja' => '作成','de' => 'Erstellen', 'es' => 'Crear', 'fr' => 'Créer'],
            'reset' => ['en' => 'Reset', 'bn' => 'রিসেট করুন','hi' => 'रीसेट करें','fa' => 'بازنشانی','ar' => 'إعادة ضبط','ur' => 'ری سیٹ کریں','zh' => '重置','ko' => '리셋','ja' => 'リセット','de' => 'Zurücksetzen', 'es' => 'Restablecer', 'fr' => 'Réinitialiser'],
            'loading' => ['en' => 'Loading', 'bn' => 'লোড হচ্ছে','hi' => 'लोड हो रहा है','fa' => 'در حال بارگذاری','ar' => 'جار التحميل','ur' => 'لوڈ ہو رہا ہے','zh' => '加载中','ko' => '로딩 중','ja' => '読み込み中','de' => 'Wird geladen', 'es' => 'Cargando', 'fr' => 'Chargement'],
            'no_data_found' => ['en' => 'No Data Found', 'bn' => 'কোনো ডেটা পাওয়া যায়নি','hi' => 'कोई डेटा नहीं मिला','fa' => 'هیچ داده ای یافت نشد','ar' => 'لم يتم العثور على بيانات','ur' => 'کوئی ڈیٹا نہیں ملا','zh' => '未找到数据','ko' => '데이터가 없습니다','ja' => 'データが見つかりません','de' => 'Keine Daten gefunden', 'es' => 'No se encontraron datos', 'fr' => 'Aucune donnée trouvée'],
            'actions' => ['en' => 'Actions', 'bn' => 'কর্ম','hi' => 'क्रियाएं','fa' => 'اقدامات','ar' => 'إجراءات','ur' => 'عملیات','zh' => '操作','ko' => '행원','ja' => 'アクション','de' => 'Aktionen', 'es' => 'Acciones', 'fr' => 'Actions'],
            'view' => ['en' => 'View', 'bn' => 'দেখুন','hi' => 'देखें','fa' => 'مشاهده','ar' => 'عرض','ur' => 'دیکھیں','zh' => '查看','ko' => '보기','ja' => '見る','de' => 'Ansehen', 'es' => 'Ver', 'fr' => 'Voir'],
            'back' => ['en' => 'Back', 'bn' => 'পেছনে','hi' => 'वापस','fa' => 'بازگشت','ar' => 'عودة','ur' => 'واپس','zh' => '返回','ko' => '되아기','ja' => '戻る','de' => 'Zurück', 'es' => 'Atrás', 'fr' => 'Retour'],
            'home_page' => ['en' => 'Home Page', 'bn' => 'হোম পেজ','hi' => 'होम पेज','fa' => 'صفحه اصلی','ar' => 'الصفحة الرئيسية','ur' => 'ہوم پیج','zh' => '主页','ko' => '홈 페이지','ja' => 'ホームページ','de' => 'Startseite', 'es' => 'Página de inicio', 'fr' => 'Page d\'accueil'],
            'dashboard_overview' => ['en' => 'Dashboard Overview', 'bn' => 'ড্যাশবোর্ড ওভারভিউ','hi' => 'डैशबोर्ड अवलोकन','fa' => 'نمای کلی داشبورد','ar' => 'نظرة عامة على لوحة القيادة','ur' => 'ڈیش بورڈ کا جائزہ','zh' => '仪表板概览','ko' => '대시포드 개요','ja' => 'ダッシュボードの概要','de' => 'Dashboard-Übersicht', 'es' => 'Resumen del tablero', 'fr' => 'Aperçu du tableau de bord'],
            'notifications' => ['en' => 'Notifications', 'bn' => 'বিজ্ঞপ্তি','hi' => 'सूचनाएं','fa' => 'اطلاعیه‌ها','ar' => 'الإشعارات','ur' => 'اطلاعات','zh' => '通知','ko' => '토이티어스','ja' => '通知','de' => 'Benachrichtigungen', 'es' => 'Notificaciones', 'fr' => 'Notifications'],
            'greetings' => ['en' => 'Greetings', 'bn' => 'অভিনন্দন','hi' => 'शुभकामनाएं','fa' => 'تبریکات','ar' => 'تحيات','ur' => 'مبارکباد','zh' => '问候语','ko' => '그릿잉스','ja' => '挨拶','de' => 'Grüße', 'es' => 'Saludos', 'fr' => 'Salutations'],
            'thank_you' => ['en' => 'Thank You', 'bn' => 'ধন্যবাদ','hi' => 'धन्यवाद','fa' => 'متشکرم','ar' => 'شكرا لك','ur' => 'شکریہ','zh' => '谢谢你','ko' => '강한 고막합니다','ja' => 'ありがとう','de' => 'Danke', 'es' => 'Gracias', 'fr' => 'Merci'],
            'good_morning' => ['en' => 'Good Morning', 'bn' => 'সুপ্রভাত','hi' => 'सुप्रभात','fa' => 'صبح بخیر','ar' => 'صباح الخير','ur' => 'صبح بخیر','zh' => '早上好','ko' => '오전 아냠','ja' => 'おはようございます','de' => 'Guten Morgen', 'es' => 'Buenos días', 'fr' => 'Bonjour'],
        ];

        $langCodes = ['en', 'bn', 'hi', 'fa', 'ar', 'ur', 'zh', 'ko', 'ja', 'de', 'es', 'fr'];

        foreach ($translations as $key => $values) {
            foreach ($values as $langCodes => $text) {
                if (empty($text)) {
                    $values[$langCodes] = $values['en'];
                }
                UiTranslation::create([
                    'language_id' => Language::where('code', $langCodes)->first()->id,
                    'group'       => 'ui',
                    'key'         => $key,
                    'value'       => $text, 
                ]);
            }
            
        }

        $this->command->info('Language table seeded!');
        $this->command->info('UI Translations seeded: ' . count($translations) . ' entries added.');
    }
}