<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use App\Models\UiTranslation;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        // Refresh UI translations without breaking foreign keys to languages.
        UiTranslation::query()->delete();

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
                'native_name' => 'हिन्दी',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4,
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'native_name' => 'Español',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 5,
            ],
            [
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }

        $this->command->info('Languages seeded successfully!');
        $this->createUiTranslations();
    }

    private function createUiTranslations(): void
    {
        $translations = [
            // Auth translations
            'auth.login.success' => [
                'en' => 'Login successful', 
                'bn' => 'লগইন সফল', 
                'hi' => 'लॉगिन सफल', 
                'ar' => 'تسجيل الدخول ناجح', 
                'es' => 'Inicio de sesión exitoso', 
                'fr' => 'Connexion réussie'
                ],
            'auth.login.error' => [
                'en' => 'Invalid credentials', 
                'bn' => 'ভুল তথ্য', 
                'hi' => 'गलत क्रेडेंशियल्स', 
                'ar' => 'بيانات الاعتماد غير صالحة', 
                'es' => 'Credenciales inválidas', 
                'fr' => 'Identifiants invalides'
            ],
            'auth.register.success' => [
                'en' => 'Registration successful', 
                'bn' => 'নিবন্ধন সফল', 
                'hi' => 'पंजीकरण सफल', 
                'ar' => 'التسجيل ناجح', 
                'es' => 'Registro exitoso', 
                'fr' => 'Inscription réussie'
            ],
            'auth.logout.success' => [
                'en' => 'Logout successful', 
                'bn' => 'লগআউট সফল', 
                'hi' => 'लॉगआउट सफल', 
                'ar' => 'تسجيل الخروج ناجح', 
                'es' => 'Cierre de sesión exitoso', 
                'fr' => 'Déconnexion réussie'
            ],

            // Common UI
            'common.welcome' => [
                'en' => 'Welcome', 
                'bn' => 'স্বাগতম', 
                'hi' => 'स्वागत है', 
                'ar' => 'مرحبا', 
                'es' => 'Bienvenido', 
                'fr' => 'Bienvenue'
            ],
            'common.dashboard' => [
                'en' => 'Dashboard', 
                'bn' => 'ড্যাশবোর্ড', 
                'hi' => 'डैশबोर्ड', 
                'ar' => 'لوحة القيادة', 
                'es' => 'Tablero', 
                'fr' => 'Tableau de bord'
            ],
            'common.profile' => [
                'en' => 'Profile', 
                'bn' => 'প্রোফাইল', 
                'hi' => 'प्रोफ़ाइल', 
                'ar' => 'الملف الشخصي', 
                'es' => 'Perfil', 
                'fr' => 'Profil'
            ],
            'common.settings' => [
                'en' => 'Settings', 
                'bn' => 'সেটিংস', 
                'hi' => 'सेटिंग्स', 
                'ar' => 'الإعدادات', 
                'es' => 'Configuración', 
                'fr' => 'Paramètres'
            ],
            'common.save' => [
                'en' => 'Save', 
                'bn' => 'সংরক্ষণ', 
                'hi' => 'सहेजें', 
                'ar' => 'حفظ', 
                'es' => 'Guardar', 
                'fr' => 'Enregistrer'
            ],
            'common.cancel' => [
                'en' => 'Cancel', 
                'bn' => 'বাতিল', 
                'hi' => 'रद्द करें', 
                'ar' => 'إلغاء', 
                'es' => 'Cancelar', 
                'fr' => 'Annuler'
            ],
            'common.edit' => [
                'en' => 'Edit', 
                'bn' => 'সম্পাদনা', 
                'hi' => 'संपादित करें', 
                'ar' => 'تعديل', 
                'es' => 'Editar', 
                'fr' => 'Modifier'
            ],
            'common.delete' => [
                'en' => 'Delete', 
                'bn' => 'মুছে ফেলুন', 
                'hi' => 'हटाएं', 
                'ar' => 'حذف', 
                'es' => 'Eliminar', 
                'fr' => 'Supprimer'
            ],
            'common.create' => [
                'en' => 'Create', 
                'bn' => 'তৈরি করুন', 
                'hi' => 'बनाएं', 
                'ar' => 'إنشاء',
                'es' => 'Crear', 
                'fr' => 'Créer'
            ],
            'common.name' => [
                'en' => 'Name', 
                'bn' => 'নাম', 
                'hi' => 'नाम', 
                'ar' => 'اسم', 
                'es' => 'Nombre', 
                'fr' => 'Nom'
            ],
            'common.email' => [
                'en' => 'Email', 
                'bn' => 'ইমেইল', 
                'hi' => 'ईमेल', 
                'ar' => 'البريد الإلكتروني', 
                'es' => 'Correo electrónico', 
                'fr' => 'E-mail'
            ],
            'common.phone' => [
                'en' => 'Phone', 
                'bn' => 'ফোন', 
                'hi' => 'फोन', 
                'ar' => 'هاتف', 
                'es' => 'Teléfono', 
                'fr' => 'Téléphone'
            ],
            'common.roles' => [
                'en' => 'Roles', 
                'bn' => 'ভূমিকা', 
                'hi' => 'भूमिकाएं', 
                'ar' => 'الأدوار', 
                'es' => 'Roles', 
                'fr' => 'Rôles'
            ],
            'common.status' => [
                'en' => 'Status', 
                'bn' => 'অবস্থা', 
                'hi' => 'स्थिति', 
                'ar' => 'الحالة', 
                'es' => 'Estado', 
                'fr' => 'Statut'
            ],
            'common.actions' => [
                'en' => 'Actions', 
                'bn' => 'কার্যক্রম', 
                'hi' => 'कार्य', 
                'ar' => 'العمليات', 
                'es' => 'Acciones', 
                'fr' => 'Actions'
            ],
            'common.created_at' => [
                'en' => 'Created At', 
                'bn' => 'তৈরি হয়েছে', 
                'hi' => 'निर्मित दिनांक', 
                'ar' => 'تاريخ الإنشاء', 
                'es' => 'Creado el', 
                'fr' => 'Créé le'
            ],
            'users.title' => [
                'en' => 'Users Management', 
                'bn' => 'ব্যবহারকারী পরিচালনা', 
                'hi' => 'उपयोगकर्ता प्रबंधन', 
                'ar' => 'إدارة المستخدمين', 
                'es' => 'Gestión de usuarios', 
                'fr' => 'Gestion des utilisateurs'
            ],
            'users.description' => [
                'en' => 'Manage system users', 
                'bn' => 'সিস্টেম ব্যবহারকারী পরিচালনা', 
                'hi' => 'सिस्टम उपयोगकर्ता प्रबंधित करें', 
                'ar' => 'إدارة مستخدمي النظام', 
                'es' => 'Gestionar usuarios del sistema', 
                'fr' => 'Gérer les utilisateurs du système'
            ],
            'roles.title' => [
                'en' => 'Roles Management', 
                'bn' => 'ভূমিকা পরিচালনা', 
                'hi' => 'भूमिका प्रबंधन', 
                'ar' => 'إدارة الأدوار', 
                'es' => 'Gestión de roles', 
                'fr' => 'Gestion des rôles'
            ],
            'roles.description' => [
                'en' => 'Manage and monitor all system roles', 
                'bn' => 'সিস্টেমের সমস্ত ভূমিকা পরিচালনা এবং পর্যবেক্ষণ করুন', 
                'hi' => 'सिस्टम भूमिकाओं को प्रबंधित करें', 
                'ar' => 'إدارة الصلاحيات للنظام', 
                'es' => 'Gestionar roles del sistema', 
                'fr' => 'Gérer les rôles du système'
            ],
            'permissions.title' => [
                'en' => 'Permissions Management', 
                'bn' => 'অনুমতি পরিচালনা', 
                'hi' => 'अनुमति प्रबंधन', 
                'ar' => 'إدارة الصلاحيات', 
                'es' => 'Gestión de permisos', 
                'fr' => 'Gestion des permissions'
            ],
            'permissions.description' => [
                'en' => 'Manage and monitor all system permissions', 
                'bn' => 'সিস্টেমের সমস্ত অনুমতি পরিচালনা এবং পর্যবেক্ষণ করুন', 
                'hi' => 'सिस्टम अनुमतियों को प्रबंधित करें', 
                'ar' => 'إدارة الصلاحيات للنظام', 
                'es' => 'Gestionar permisos del sistema', 
                'fr' => 'Gérer les permissions du système'
            ],

            // Landing page
            'hero.tagline' => [
                'en' => '🚀 Modern Full-Stack Starter Kit', 
                'bn' => '🚀 আধুনিক ফুল-স্ট্যাক স্টার্টার কিট', 
                'ar' => '🚀 مجموعة البدء الكاملة للمطورين', 
                'hi' => '🚀 आधुनिक पूर्ण-स्टैक स्टार्टर किट', 
                'es' => '🚀 Kit de inicio completo para desarrolladores', 
                'fr' => '🚀 Kit de démarrage complet pour les développeurs'
            ],
            'hero.title.part1' => [
                'en' => 'Build with', 
                'bn' => 'তৈরি করুন', 
                'hi' => 'बनाएं', 
                'ar' => 'إنشاء', 
                'es' => 'Crear', 
                'fr' => 'Créer'
            ],
            'hero.title.part2' => [
                'en' => 'Nuxt.js & Laravel', 
                'bn' => 'Nuxt.js ও Laravel দিয়ে', 
                'hi' => 'Nuxt.js और Laravel के साथ', 
                'ar' => 'Nuxt.js و Laravel', 
                'es' => 'Nuxt.js y Laravel', 
                'fr' => 'Nuxt.js et Laravel'
            ],
            'hero.description' => [
                'en' => 'A production-ready starter kit combining the power of Nuxt.js frontend with Laravel API backend. Explore modern features, best practices, and clean architecture patterns in a cohesive project.', 
                'bn' => 'Nuxt.js ফ্রন্টএন্ড এবং Laravel API ব্যাকএন্ডের সমন্বয়ে তৈরি একটি প্রোডাকশন-রেডি স্টার্টার কিট। আধুনিক ফিচার, বেস্ট প্র্যাকটিস এবং ক্লিন আর্কিটেকচার একসাথে অন্বেষণ করুন।', 
                'hi' => 'Nuxt.js फ्रंटएंड और Laravel API बैकएंड के संयोजन से बना एक प्रोडक्शन-रेडी स्टार्टर किट। आधुनिक फीचर्स, बेस्ट प्रैक्टिसेस और क्लीन आर्किटेक्चर पैटर्न को एक साथ एक्सप्लोर करें।', 
                'ar' => 'مجموعة بدء جاهزة للإنتاج تجمع بين قوة واجهة Nuxt.js الأمامية وواجهة Laravel API الخلفية. استكشف الميزات الحديثة وأفضل الممارسات وأنماط الهندسة النظيفة في مشروع متماسك.', 
                'es' => 'Un kit de inicio listo para producción que combina el poder del frontend Nuxt.js con el backend API de Laravel. Explora características modernas, mejores prácticas y patrones de arquitectura limpia en un proyecto cohesivo.', 
                'fr' => 'Un kit de démarrage prêt pour la production combinant la puissance du frontend Nuxt.js avec le backend API Laravel. Explorez les fonctionnalités modernes, les meilleures pratiques et les modèles d\'architecture propre dans un projet cohérent.'
            ],
            'hero.get_started' => [
                'en' => 'Get Started', 
                'bn' => 'শুরু করুন', 
                'hi' => 'শুরূ کریں', 
                'ar' => 'ابدأ الآن', 
                'es' => 'Comenzar', 
                'fr' => 'Commencer'
            ],
            'hero.view_demo' => [
                'en' => 'View Demo', 
                'bn' => 'ডেমো দেখুন', 
                'hi' => 'ڈیمو دیکھیں', 
                'ar' => 'عرض العرض التجريبي', 
                'es' => 'Ver demo', 
                'fr' => 'Voir la démo'
            ],
            'hero.production_ready' => [
                'en' => 'Production Ready', 
                'bn' => 'প্রোডাকশন রেডি', 
                'hi' => 'प्रोडक्शन रेडी', 
                'ar' => 'جاهز للإنتاج', 
                'es' => 'Listo para producción', 
                'fr' => 'Prêt pour la production'
            ],
            'features.tagline' => [
                'en' => '✨ Key Features', 
                'bn' => '✨ মূল ফিচারসমূহ', 
                'hi' => '✨ मुख्य विशेषताएं', 
                'ar' => '✨ الميزات الرئيسية', 
                'es' => '✨ Características clave', 
                'fr' => '✨ Caractéristiques clés'
            ],
            'features.title.part1' => [
                'en' => 'Everything You Need to', 
                'bn' => 'আপনার যা যা প্রয়োজন', 
                'hi' => 'आपको जो कुछ भी चाहिए', 
                'ar' => 'كل ما تحتاجه', 
                'es' => 'Todo lo que necesitas para', 
                'fr' => 'Tout ce dont vous avez besoin para'
            ],
            'features.title.part2' => [
                'en' => 'Build Modern Apps', 
                'bn' => 'আধুনিক অ্যাপ তৈরিতে', 
                'hi' => 'आধুনিক ऐप्स बनाने के लिए', 
                'ar' => 'بناء تطبيقات حديثة', 
                'es' => 'Construir aplicaciones modernas', 
                'fr' => 'Construire des applications modernes'
            ],
            'features.description' => [
                'en' => 'Our starter kit includes all the essential features to jumpstart your development process with best practices and clean architecture.', 
                'bn' => 'আমাদের স্টার্টার কিটে রয়েছে প্রয়োজনীয় সব ফিচার, যা বেস্ট প্র্যাকটিস ও ক্লিন আর্কিটেকচারের মাধ্যমে দ্রুত ডেভেলপমেন্ট শুরু করতে সাহায্য করে।', 
                'hi' => 'हमारे स्टार्टर किट में सभी आवश्यक विशेषताएं शामिल हैं जो आपके विकास प्रक्रिया को बेस्ट प्रैक्टिसेस और क्लीन आर्किटेक्चर के साथ तेजी से शुरू करने में मदद करती हैं।', 
                'ar' => 'تتضمن مجموعة البدء الخاصة بنا جميع الميزات الأساسية لبدء عملية التطوير الخاصة بك بأفضل الممارسات والهندسة النظيفة.', 
                'es' => 'Nuestro kit de inicio incluye todas las características esenciales para acelerar tu proceso de desarrollo con las mejores prácticas y una arquitectura limpia.', 
                'fr' => 'Notre kit de démarrage comprend toutes les fonctionnalités essentielles pour accélérer votre processus de développement avec les meilleures pratiques et une architecture propre.'
            ],
            'feature.auth.title' => [
                'en' => 'Secure Authentication', 
                'bn' => 'নিরাপদ অথেনটিকেশন', 
                'hi' => 'सुरक्षित प्रमाणीकरण', 
                'ar' => 'المصادقة الآمنة', 
                'es' => 'Autenticación segura', 
                'fr' => 'Authentification sécurisée'
            ],
            'feature.auth.description' => [
                'en' => 'Secure authentication with JWT tokens and password encryption.', 
                'bn' => 'জিওটি টোকেন এবং পাসওয়ার্ড এনক্রিপ্শনে নিরাপদ অথেনটিকেশন।', 
                'hi' => 'जीटी टोकन एवं पासवर्ड एनक्रिप्शन के साथ सुरक्षित प्रमाणीकरण।', 
                'ar' => 'المصادقة الآمنة مع التوكنات الجيتي والتشفير الباسورد.', 
                'es' => 'Autenticación segura con tokens JWT y encriptación de contrasenas.', 
                'fr' => 'Authentification securisée avec les jetons JWT et l\'encryption du mot de passe.'
            ],
            'feature.profile.title' => [
                'en' => 'User Profile Management', 
                'bn' => 'ব্যবহারকারী প্রোফাইল ব্যবস্থাপনা', 
                'hi' => 'उपयोगकर्ता प्रोफ़ाइल प्रबंधन', 
                'ar' => 'إدارة ملف المستخدم', 
                'es' => 'Gestión de perfil de usuario', 
                'fr' => 'Gestion du profil utilisateur'
            ],
            'feature.profile.description' => [
                'en' => 'Allow users to view and update their profile information securely.', 
                'bn' => 'ব্যবহারকারীদের তাদের প্রোফাইল তথ্য নিরাপদে দেখার এবং আপডেট করার অনুমতি দিন।', 
                'hi' => 'उपयोगकर्ताओं को उनके प्रोफ़ाइल जानकारी को सुरक्षित रूप से देखने और अपडेट करने की अनुमति दें।', 
                'ar' => 'السماح للمستخدمين بعرض وتحديث معلومات ملفهم الشخصي بأمان.', 
                'es' => 'Permitir a los usuarios ver y actualizar su información de perfil de forma segura.', 
                'fr' => 'Permettre aux utilisateurs de voir et de mettre à jour leurs informations de profil en toute sécurité.'
            ],
            'feature.i18n.title' => [
                'en' => 'Internationalization (i18n)', 
                'bn' => 'আন্তর্জাতিকীকরণ (i18n)', 
                'hi' => 'अंतर्राष्ट्रीयकरण (i18n)', 
                'ar' => 'الترجمة (i18n)', 
                'es' => 'Internacionalización (i18n)', 
                'fr' => 'Internationalisation (i18n)'
            ],
            'feature.i18n.description' => [
                'en' => 'Support for multiple languages with dynamic UI translations.', 
                'bn' => 'ডাইনামিক UI অনুবাদের মাধ্যমে একাধিক ভাষার জন্য সমর্থন।', 
                'hi' => 'डायनेमिक UI अनुवाद के साथ कई भाषाओं के लिए समर्थन।', 
                'ar' => 'دعم للغات متعددة مع ترجمات واجهة المستخدم الديناميكية.', 
                'es' => 'Soporte para múltiples idiomas con traducciones dinámicas de la interfaz de usuario.', 
                'fr' => 'Support pour plusieurs langues avec des traductions dynamiques de l\'interface utilisateur.'
            ],
            'feature.architecture.title' => [
                'en' => 'Clean Architecture', 
                'bn' => 'ক্লিন আর্কিটেকচার', 
                'hi' => 'क्लीन आर्किटेक्चर', 
                'ar' => 'الهندسة النظيفة', 
                'es' => 'Arquitectura limpia', 
                'fr' => 'Architecture propre'
            ],
            'feature.architecture.description' => [
                'en' => 'A clean, testable architecture to ensure the stability and maintainability of the application.',
                'bn' => 'অ্যাপ্লিকেশনের স্থিতিশীলতা এবং রক্ষণাবেক্ষণযোগ্যতা নিশ্চিত করার জন্য একটি পরিষ্কার, পরীক্ষণযোগ্য আর্কিটেকচার।', 
                'hi' => 'एक स्पष्ट, परीक्षण योग्य आर्किटेक्चर जो एप्लिकेशन की स्थिरता एवं रखरखाव के लिए निश्चित करता है।', 
                'ar' => 'هندسة نظيفة قابلة للاختبار لضمان استقرار وسهولة صيانة التطبيق.', 
                'es' => 'Una arquitectura limpia y testeable para garantizar la estabilidad y mantenibilidad de la aplicación.', 
                'fr' => 'Une architecture propre et testable pour garantir la stabilité et la maintenabilité de l\'application.'
            ],
            'feature.best_practices.title' => [
                'en' => 'Best Practices', 
                'bn' => 'সেরা অনুশীলন', 
                'hi' => 'सर्वश्रेष्ठ प्रथाएं', 
                'ar' => 'أفضل الممارسات', 
                'es' => 'Mejores prácticas', 
                'fr' => 'Meilleures pratiques'
            ],
            'feature.best_practices.description' => [
                'en' => 'Follow industry best practices for coding standards, security, and performance.', 
                'bn' => 'কোডিং স্ট্যান্ডার্ড, নিরাপত্তা এবং কর্মক্ষমতার জন্য শিল্পের সেরা অনুশীলন অনুসরণ করুন।', 
                'hi' => 'कोडिंग मानकों, सुरक्षा और प्रदर्शन के लिए उद्योग की सर्वोत्तम प्रथाओं का पालन करें।', 
                'ar' => 'اتبع أفضل الممارسات الصناعية لمعايير الترميز والأمان والأداء.', 
                'es' => 'Sigue las mejores prácticas de la industria para los estándares de codificación, seguridad y rendimiento.', 
                'fr' => 'Suivez les meilleures pratiques de l\'industrie pour les normes de codage, la sécurité et le rendement.'
            ],
            'feature.seeding.title' => [
                'en' => 'Dynamic Content Seeding', 
                'bn' => 'ডাইনামিক কন্টেন্ট সিডিং', 
                'hi' => 'डायनेमिक कंटेंट सीडिंग', 
                'ar' => 'تعبئة المحتوى الديناميكي', 
                'es' => 'Seeding de contenido dinámico', 
                'fr' => 'Seeding de contenu dynamique'
            ],
            'feature.seeding.description' => [
                'en' => 'Pre-configured seeders for dynamic content to quickly populate your application with test data.', 
                'bn' => 'টেস্ট ডেটা দিয়ে দ্রুত অ্যাপ পূরণ করার জন্য প্রি-কনফিগার্ড সিডার।', 
                'hi' => 'टेस्ट डेटा के साथ आपके एप्लिकेशन को जल्दी से भरने के लिए प्री-कॉन्फ़िगर किए गए सीडर्स।', 
                'ar' => 'سيدرز مهيأة مسبقًا للمحتوى الديناميكي لملء تطبيقك بسرعة ببيانات الاختبار.', 
                'es' => 'Seeders preconfigurados para contenido dinámico para poblar rápidamente tu aplicación con datos de prueba.', 
                'fr' => 'Seeders préconfigurés pour du contenu dynamique afin de peupler rapidement votre application avec des données de test.'
            ],
            'tech.tagline' => [
                'en' => '💻 Modern Tech Stack', 
                'bn' => '💻 আধুনিক টেক স্ট্যাক', 
                'hi' => '💻 आधुनिक टेक स्टैक', 
                'ar' => '💻 مجموعة التقنيات الحديثة', 
                'es' => '💻 Pila de tecnología moderna', 
                'fr' => '💻 Pile de technologie moderne'
            ],
            'tech.title.part1' => [
                'en' => 'Built with', 
                'bn' => 'তৈরি হয়েছে', 
                'hi' => 'के साथ बनाया गया', 
                'ar' => 'بُنيت مع', 
                'es' => 'Construido con', 
                'fr' => 'Construit avec'
            ],
            'tech.title.part2' => [
                'en' => 'Latest Technologies', 
                'bn' => 'সর্বশেষ প্রযুক্তিতে', 
                'hi' => 'নবীনতম তকনীকোং কে সাথ', 
                'ar' => 'أحدث التقنيات', 
                'es' => 'Últimas tecnologías', 
                'fr' => 'Dernières technologies'
            ],
            'tech.description' => [
                'en' => 'Our starter kit leverages the latest versions of industry-leading frameworks to ensure your project is modern, scalable, and maintainable.', 
                'bn' => 'আমাদের স্টার্টার কিটে সর্বাধুনিক ফ্রেমওয়ার্কের সর্বশেষ সংস্করণ ব্যবহার করা হয়েছে, যাতে আপনার প্রজেক্ট আধুনিক, স্কেলযোগ্য এবং রক্ষণাবেক্ষণযোগ্য থাকে।', 
                'hi' => 'हमारे स्टार्टर किट में उद्योग के अग्रणी फ्रेमवर्क के नवीनतम संस्करणों का उपयोग किया गया है ताकि आपका प्रोजेक्ट आधुनिक, स्केलेबल और रखरखाव योग्य हो।', 
                'ar' => 'يستخدم مجموعة البدء الخاصة بنا أحدث إصدارات الأطر الرائدة في الصناعة لضمان أن مشروعك حديث وقابل للتوسع وسهل الصيانة.', 
                'es' => 'Nuestro kit de inicio aprovecha las últimas versiones de los frameworks líderes en la industria para garantizar que tu proyecto sea moderno, escalable y mantenible.', 
                'fr' => 'Notre kit de démarrage tire parti des dernières versions des frameworks leaders de l\'industrie pour garantir que votre projet soit moderne, évolutif et maintenable.'
            ],
            'tech.laravel.title' => [
                'en' => 'Laravel API', 
                'bn' => 'Laravel API',
                'hi' => 'Laravel API',
                'ar' => 'Laravel API',
                'es' => 'Laravel API',
                'fr' => 'Laravel API'
            ],
            'tech.laravel.subtitle' => [
                'en' => 'Backend Framework', 
                'bn' => 'ব্যাকএন্ড ফ্রেমওয়ার্ক',
                'hi' => 'बैकएंड फ्रेमवर्क',
                'ar' => 'إطار العمل الخلفي',
                'es' => 'Framework de backend',
                'fr' => 'Framework backend'
            ],
            'tech.laravel.feature1' => [
                'en' => 'RESTful API development', 
                'bn' => 'RESTful API ডেভেলপমেন্ট',
                'hi' => 'RESTful API विकास',
                'ar' => 'تطوير RESTful API',
                'es' => 'Desarrollo de API RESTful',
                'fr' => 'Développement d\'API RESTful'
            ],
            'tech.laravel.feature2' => [
                'en' => 'Repository-Service pattern', 
                'bn' => 'Repository-Service প্যাটার্ন',
                'hi' => 'Repository-Service पैटर्न',
                'ar' => 'معالجة Repository-Service',
                'es' => 'Patrón Repository-Service',
                'fr' => 'Modèle Repository-Service'
            ],
            'tech.laravel.feature3' => [
                'en' => 'Event-driven architecture', 
                'bn' => 'ইভেন্ট-ড্রিভেন আর্কিটেকচার',
                'hi' => 'इवेंट-ड्रिवेन आर्किटेक्चर',
                'ar' => 'هندسة تعتمد على الأحداث',
                'es' => 'Arquitectura basada en eventos',
                'fr' => 'Architecture basée sur les événements'
            ],
            'tech.laravel.feature4' => [
                'en' => 'API resource transformation', 
                'bn' => 'API রিসোর্স ট্রান্সফরমেশন',
                'hi' => 'API रिसोर्स ट्रान्सफर्मेशन',
                'ar' => 'تحويل API الرесورس',
                'es' => 'Transformación de recursos de API',
                'fr' => 'Transformation des ressources de l\'API'
            ],
            'tech.nuxt.title' => [
                'en' => 'Nuxt.js', 
                'bn' => 'Nuxt.js',
                'hi' => 'Nuxt.js',
                'ar' => 'Nuxt.js',
                'es' => 'Nuxt.js',
                'fr' => 'Nuxt.js'
            ],
            'tech.nuxt.subtitle' => [
                'en' => 'Frontend Framework', 
                'bn' => 'ফ্রন্টএন্ড ফ্রেমওয়ার্ক',
                'hi' => 'फ्रंटएंड फ्रेमवर्क',
                'ar' => 'إطار العمل القصوى',
                'es' => 'Framework de frontend',
                'fr' => 'Framework frontend'
            ],
            'tech.nuxt.feature1' => [
                'en' => 'Vue 3 Composition API', 
                'bn' => 'Vue 3 Composition API',
                'hi' => 'Vue 3 Composition API',
                'ar' => 'Vue 3 Composition API',
                'es' => 'Vue 3 Composition API',
                'fr' => 'Vue 3 Composition API'
            ],
            'tech.nuxt.feature2' => [
                'en' => 'Server-side rendering (SSR)', 
                'bn' => 'সার্ভার-সাইড রেন্ডারিং (SSR)', 
                'hi' => 'सर्वर-साइड रेंडरिंग (SSR)', 
                'ar' => 'تصنيع الخادم (SSR)', 
                'es' => 'Renderizado del lado del servidor (SSR)', 
                'fr' => 'Rendu du serveur (SSR)'
            ],
            'tech.nuxt.feature3' => [
                'en' => 'Internationalization (i18n)', 
                'bn' => 'আন্তর্জাতিকীকরণ (i18n)',
                'hi' => 'अंतरराष्ट्रीयकरण (i18n)',
                'ar' => 'الدولية (i18n)',
                'es' => 'Internacionalización (i18n)',
                'fr' => 'Internationalisation (i18n)'
            ],
            'tech.nuxt.feature4' => [
                'en' => 'Component-based architecture', 
                'bn' => 'কম্পোনেন্ট-ভিত্তিক আর্কিটেকচার', 
                'hi' => 'कंपोनेंट-बेस्ड आर्किटेक्चर', 
                'ar' => 'هندسة تعتمد على الأحداث', 
                'es' => 'Arquitectura basada en componentes',
            ],

            // Validation messages
            'validation.required' => [
                'en' => 'This field is required', 
                'bn' => 'এই ক্ষেত্রটি প্রয়োজন', 
                'hi' => 'यह फ़ील्ड आवश्यक है', 
                'ar' => 'هذا الحقل مطلوب', 
                'es' => 'Este campo es obligatorio', 
                'fr' => 'Ce champ est obligatoire'
            ],
            'validation.email' => [
                'en' => 'Please enter a valid email address', 
                'bn' => 'সঠিক ইমেইল দিন', 
                'hi' => 'कृपया मान्य ईमेल दर्ज करें', 
                'ar' => 'الرجاء إدخال عنوان بريد إلكتروني صالح', 
                'es' => 'Por favor ingrese un correo electrónico válido', 
                'fr' => 'Veuillez entrer une adresse email valide'
            ],
            'validation.min' => [
                'en' => 'Minimum :min characters required', 
                'bn' => 'ন্যূনতম :min অক্ষর প্রয়োজন', 
                'hi' => 'न्यूनतम :min अक्षर आवश्यक', 
                'ar' => 'الحد الأدنى :min حرف مطلوب', 
                'es' => 'Se requieren al menos :min caracteres', 
                'fr' => 'Minimum :min caractères requis'
            ],
            'success.created' => [
                'en' => 'Created successfully', 
                'bn' => 'সফলভাবে তৈরি হয়েছে', 
                'hi' => 'सफलतापूर्वक बनाया गया', 
                'ar' => 'تم الإنشاء بنجاح', 
                'es' => 'Creado exitosamente', 
                'fr' => 'Créé avec succès'
            ],
            'error.not_found' => [
                'en' => 'Resource not found', 
                'bn' => 'রিসোর্স পাওয়া যায়নি', 
                'hi' => 'संसाधन नहीं मिला', 
                'ar' => 'الموارد غير موجودة', 
                'es' => 'Recurso no encontrado', 
                'fr' => 'Ressource non trouvée'
            ],
            'error.unauthorized' => [
                'en' => 'Unauthorized access', 
                'bn' => 'অননুমোদিত অ্যাক্সেস', 
                'hi' => 'अनधिकृत पहुंচ', 
                'ar' => 'وصول غير مصرح به', 
                'es' => 'Acceso no autorizado', 
                'fr' => 'Accès non autorisé'
            ],
            'error.server' => [
                'en' => 'Server error occurred', 
                'bn' => 'সার্ভার ত্রুটি ঘটেছে', 
                'hi' => 'सर्वर त्रुटि हुई', 
                'ar' => 'حدث خطأ في الخادم', 
                'es' => 'Ocurrió un error en el servidor', 
                'fr' => 'Une erreur serveur est survenue'
            ],
        ];

        $languages = Language::all();

        foreach ($translations as $key => $values) {
            foreach ($languages as $language) {
                $value = $values[$language->code] ?? $values['en'] ?? $key;

                UiTranslation::create([
                    'group' => 'ui',
                    'key' => $key,
                    'value' => $value,
                    'language_id' => $language->id,
                ]);
            }
        }

        $this->command->info('UI translations seeded successfully!');
        $this->command->info('Total UI translations: ' . UiTranslation::count());
    }
}
