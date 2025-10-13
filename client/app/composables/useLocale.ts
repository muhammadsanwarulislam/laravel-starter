export const useLocale = () => {
    const locale = useState('locale', () => useCookie('locale').value || 'bn');
    const translations = useState('translations', () => ({}));
    const languages = useState('languages', () => []);

    // Fetch available languages
    const fetchLanguages = async () => {
        try {
            const res = await $http('/languages?offset=0&limit=10&option=list');
            if (res.error) {
                console.error('Failed to fetch languages:', res.error);
                languages.value = [];
            } else {
                languages.value = res.data || [];
            }
        } catch (error) {
            console.error('Failed to fetch languages:', error);
            languages.value = [];
        }
    };

    // Fetch translations for specific locale
    const fetchTranslations = async (selectedLocale: string) => {
        try {
            const res = await $http(`/translations/${selectedLocale}`);
            if (res.error) {
                console.error(`Failed to fetch translations for ${selectedLocale}:`, res.error);
                translations.value = {};
            } else {
                translations.value = res.data.data || {};
            }
        } catch (error) {
            console.error(`Failed to fetch translations for ${selectedLocale}:`, error);
            translations.value = {};
        }
    };

    // Translation function
    const t = (key: string) => {
        return translations.value[key] || key;
    };

    // Change locale
    const changeLocale = async (newLocale: string) => {
        locale.value = newLocale;
        useCookie('locale').value = newLocale;
        await fetchTranslations(newLocale);
    };

    // Initialize
    const initialize = async () => {
        await fetchLanguages();
        await fetchTranslations(locale.value);
    };

    return { 
        locale, 
        translations, 
        languages, 
        t, 
        changeLocale, 
        fetchLanguages,
        fetchTranslations,
        initialize
    };
};