export const $http = async <T>(
  url: string,
  options: any = {}
): Promise<{ data?: T; error?: any; code?: number }> => {
  const config = useRuntimeConfig();

  const token = useCookie('auth_token', {
    maxAge: 60 * 60 * 24 * 7,
    path: '/',
    secure: import.meta.env.MODE === 'production',
    sameSite: 'lax'
  });

  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...options.headers,
  };

  if (token.value) {
    headers['Authorization'] = `Bearer ${token.value}`;
  }

  try {
    const response = await $fetch(url, {
      baseURL: config.public.apiURL,
      headers,
      ...options,
    });

    return { data: response as T, code: 200 };
  } catch (error: any) {
    console.error('HTTP error:', error);

    if (error.status === 401) {
      token.value = null;
    }

    return {
      error: {
        message: error.data?.message || error.message || 'Request failed',
        status: error.status
      },
      code: error.status
    };
  }
};