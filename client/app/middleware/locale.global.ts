export default defineNuxtRouteMiddleware(async () => {
  const { locale, changeLocale } = useLocale();
  const userLocale = locale.value || 'bn';
  await changeLocale(userLocale);
});
