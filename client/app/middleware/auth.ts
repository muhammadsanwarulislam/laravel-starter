export default defineNuxtRouteMiddleware(async (to, from) => {
	const { fetchCurrentUser, user, token } = useAuth();

	if (!token.value) {
		return navigateTo('/', { replace: true });
	}

	if (!user.value) {
		await fetchCurrentUser();
	}

	if (!user.value) {
		return navigateTo('/', { replace: true });
	}
});