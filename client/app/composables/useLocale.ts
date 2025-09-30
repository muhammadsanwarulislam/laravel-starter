export const useLocale = () => {
    const locale = useState('locale', () => 'bn');
    const translations = useState('translations', () => ({}));
    const languages = useState('languages', () => []);

    // Fetch available languages
    const fetchLanguages = async () => {
        try {
            const { data } = await useFetch('/languages', {
                baseURL: useRuntimeConfig().public.apiURL,
                method: 'GET',
            });
            languages.value = data.value || [];
        } catch (error) {
            console.error('Failed to fetch languages:', error);
            languages.value = [];
        }
    };

    // Fetch translations for specific locale
    const fetchTranslations = async (selectedLocale: string) => {
        try {
            const { data } = await useFetch(`/translations/${selectedLocale}`, {
                baseURL: useRuntimeConfig().public.apiURL,
                method: 'GET',
            });
            translations.value = data.value || {};
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