<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\UiTranslation;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Language::query()->delete();
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
            Language::create($language);
        }

        $this->command->info('Languages seeded successfully!');
        $this->createUiTranslations();
    }

    private function createUiTranslations(): void
    {
        $translations = [
            // Auth translations
            'auth.login.success' => ['en' => 'Login successful', 'bn' => 'লগইন সফল', 'hi' => 'लॉगिन सफल', 'ar' => 'تسجيل الدخول ناجح', 'es' => 'Inicio de sesión exitoso', 'fr' => 'Connexion réussie'],
            'auth.login.error' => ['en' => 'Invalid credentials', 'bn' => 'ভুল তথ্য', 'hi' => 'गलत क्रेडेंशियल्स', 'ar' => 'بيانات الاعتماد غير صالحة', 'es' => 'Credenciales inválidas', 'fr' => 'Identifiants invalides'],
            'auth.register.success' => ['en' => 'Registration successful', 'bn' => 'নিবন্ধন সফল', 'hi' => 'पंजीकरण सफल', 'ar' => 'التسجيل ناجح', 'es' => 'Registro exitoso', 'fr' => 'Inscription réussie'],
            'auth.logout.success' => ['en' => 'Logout successful', 'bn' => 'লগআউট সফল', 'hi' => 'लॉगआउट सफल', 'ar' => 'تسجيل الخروج ناجح', 'es' => 'Cierre de sesión exitoso', 'fr' => 'Déconnexion réussie'],

            // Common UI
            'common.welcome' => ['en' => 'Welcome', 'bn' => 'স্বাগতম', 'hi' => 'स्वागत है', 'ar' => 'مرحبا', 'es' => 'Bienvenido', 'fr' => 'Bienvenue'],
            'common.dashboard' => ['en' => 'Dashboard', 'bn' => 'ড্যাশবোর্ড', 'hi' => 'डैशबोर्ड', 'ar' => 'لوحة القيادة', 'es' => 'Tablero', 'fr' => 'Tableau de bord'],
            'common.profile' => ['en' => 'Profile', 'bn' => 'প্রোফাইল', 'hi' => 'प्रोफ़ाइल', 'ar' => 'الملف الشخصي', 'es' => 'Perfil', 'fr' => 'Profil'],
            'common.settings' => ['en' => 'Settings', 'bn' => 'সেটিংস', 'hi' => 'सेटिंग्स', 'ar' => 'الإعدادات', 'es' => 'Configuración', 'fr' => 'Paramètres'],
            'common.save' => ['en' => 'Save', 'bn' => 'সংরক্ষণ', 'hi' => 'सहेजें', 'ar' => 'حفظ', 'es' => 'Guardar', 'fr' => 'Sauvegarder'],
            'common.cancel' => ['en' => 'Cancel', 'bn' => 'বাতিল', 'hi' => 'रद्द करें', 'ar' => 'إلغاء', 'es' => 'Cancelar', 'fr' => 'Annuler'],
            'common.edit' => ['en' => 'Edit', 'bn' => 'সম্পাদনা', 'hi' => 'संपादित करें', 'ar' => 'تعديل', 'es' => 'Editar', 'fr' => 'Modifier'],
            'common.delete' => ['en' => 'Delete', 'bn' => 'মুছে ফেলুন', 'hi' => 'हटाएं', 'ar' => 'حذف', 'es' => 'Eliminar', 'fr' => 'Supprimer'],
            'common.create' => ['en' => 'Create', 'bn' => 'তৈরি করুন', 'hi' => 'बनाएं', 'ar' => 'إنشاء', 'es' => 'Crear', 'fr' => 'Créer'],
            'common.view' => ['en' => 'View', 'bn' => 'দেখুন', 'hi' => 'देखें', 'ar' => 'عرض', 'es' => 'Ver', 'fr' => 'Voir'],
            'common.search' => ['en' => 'Search', 'bn' => 'অনুসন্ধান', 'hi' => 'खोज', 'ar' => 'بحث', 'es' => 'Buscar', 'fr' => 'Rechercher'],

            // User management
            'users.title' => ['en' => 'Users', 'bn' => 'ব্যবহারকারী', 'hi' => 'उपयोगकर्ता', 'ar' => 'المستخدمون', 'es' => 'Usuarios', 'fr' => 'Utilisateurs'],
            'users.name' => ['en' => 'Name', 'bn' => 'নাম', 'hi' => 'नाम', 'ar' => 'الاسم', 'es' => 'Nombre', 'fr' => 'Nom'],
            'users.email' => ['en' => 'Email', 'bn' => 'ইমেইল', 'hi' => 'ईमेल', 'ar' => 'البريد الإلكتروني', 'es' => 'Correo electrónico', 'fr' => 'Email'],
            'users.status' => ['en' => 'Status', 'bn' => 'স্ট্যাটাস', 'hi' => 'स्थिति', 'ar' => 'الحالة', 'es' => 'Estado', 'fr' => 'Statut'],
            'users.active' => ['en' => 'Active', 'bn' => 'সক্রিয়', 'hi' => 'सक्रिय', 'ar' => 'نشط', 'es' => 'Activo', 'fr' => 'Actif'],
            'users.inactive' => ['en' => 'Inactive', 'bn' => 'নিষ্ক্রিয়', 'hi' => 'निष्क्रिय', 'ar' => 'غير نشط', 'es' => 'Inactivo', 'fr' => 'Inactif'],

            // Roles & Permissions
            'roles.title' => ['en' => 'Roles', 'bn' => 'ভূমিকা', 'hi' => 'भूमिकाएं', 'ar' => 'الأدوار', 'es' => 'Roles', 'fr' => 'Rôles'],
            'permissions.title' => ['en' => 'Permissions', 'bn' => 'অনুমতি', 'hi' => 'अनुमतियाँ', 'ar' => 'الصلاحيات', 'es' => 'Permisos', 'fr' => 'Permissions'],

            // Validation messages
            'validation.required' => ['en' => 'This field is required', 'bn' => 'এই ক্ষেত্রটি প্রয়োজন', 'hi' => 'यह फ़ील्ड आवश्यक है', 'ar' => 'هذا الحقل مطلوب', 'es' => 'Este campo es obligatorio', 'fr' => 'Ce champ est obligatoire'],
            'validation.email' => ['en' => 'Please enter a valid email address', 'bn' => 'সঠিক ইমেইল দিন', 'hi' => 'कृपया मान्य ईमेल दर्ज करें', 'ar' => 'الرجاء إدخال عنوان بريد إلكتروني صالح', 'es' => 'Por favor ingrese un correo electrónico válido', 'fr' => 'Veuillez entrer une adresse email valide'],
            'validation.min' => ['en' => 'Minimum :min characters required', 'bn' => 'ন্যূনতম :min অক্ষর প্রয়োজন', 'hi' => 'न्यूनतम :min वर्ण आवश्यक', 'ar' => 'الحد الأدنى :min حرف مطلوب', 'es' => 'Se requieren al menos :min caracteres', 'fr' => 'Minimum :min caractères requis'],

            // Success messages
            'success.created' => ['en' => 'Created successfully', 'bn' => 'সফলভাবে তৈরি হয়েছে', 'hi' => 'सफलतापूर्वक बनाया गया', 'ar' => 'تم الإنشاء بنجاح', 'es' => 'Creado exitosamente', 'fr' => 'Créé avec succès'],
            'success.updated' => ['en' => 'Updated successfully', 'bn' => 'সফলভাবে আপডেট হয়েছে', 'hi' => 'सफलतापूर्वक अपडेट किया गया', 'ar' => 'تم التحديث بنجاح', 'es' => 'Actualizado exitosamente', 'fr' => 'Mis à jour avec succès'],
            'success.deleted' => ['en' => 'Deleted successfully', 'bn' => 'সফলভাবে মুছে ফেলা হয়েছে', 'hi' => 'सफलतापूर्वक हटाया गया', 'ar' => 'تم الحذف بنجاح', 'es' => 'Eliminado exitosamente', 'fr' => 'Supprimé avec succès'],

            // Error messages
            'error.not_found' => ['en' => 'Resource not found', 'bn' => 'রিসোর্স পাওয়া যায়নি', 'hi' => 'संसाधन नहीं मिला', 'ar' => 'الموارد غير موجودة', 'es' => 'Recurso no encontrado', 'fr' => 'Ressource non trouvée'],
            'error.unauthorized' => ['en' => 'Unauthorized access', 'bn' => 'অননুমোদিত অ্যাক্সেস', 'hi' => 'अनधिकृत पहुंच', 'ar' => 'وصول غير مصرح به', 'es' => 'Acceso no autorizado', 'fr' => 'Accès non autorisé'],
            'error.server' => ['en' => 'Server error occurred', 'bn' => 'সার্ভার ত্রুটি ঘটেছে', 'hi' => 'सर्वर त्रुटि हुई', 'ar' => 'حدث خطأ في الخادم', 'es' => 'Ocurrió un error en el servidor', 'fr' => 'Une erreur serveur est survenue'],
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
