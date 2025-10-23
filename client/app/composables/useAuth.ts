import { useCookie } from "#app";
import { API_ENDPOINTS } from "~/config/api";
import { useNotification } from './useNotification';

export const useAuth = () => {
  const user = useState("user", () => null);

  // Enhanced cookie configuration - make it consistent
  const token = useCookie("auth_token", {
    maxAge: 60 * 60 * 24 * 7,
    path: '/',
    secure: import.meta.env.MODE === 'production',
    sameSite: 'lax'
  });

  const { add: notify } = useNotification()

  const signin = async (credentials: { email: string; password: string }) => {
    try {
      const response = await $http<{
        access_token: string,
        message: string,
        data: {
          access_token: string,
          user: any
        }
      }>(`${API_ENDPOINTS.LOGIN}`, {
        method: "POST",
        body: credentials,
      });

      if (response.error) {
        notify(response.error.message, 'error');
        return { error: response.error };
      }

      const accessToken = response.data?.access_token || response.data?.data?.access_token;
      if (accessToken) {
        // Set token value - this should persist the cookie
        token.value = accessToken;

        // Set user data
        if (response.data?.data?.user) {
          user.value = response.data.data.user;
        } else if (response.data?.user) {
          user.value = response.data.user;
        }

        notify(response.data?.message || 'Login successful', 'success');
        return { data: response.data, success: true, code: 200 };
      }

      notify('No access token received', 'error');
      return { error: { message: "No access token received" } };

    } catch (error) {
      console.error('Login error:', error);
      notify('Login failed', 'error');
      return { error: { message: "Login failed" } };
    }
  };


  const signup = async (credentials: { name: string; email: string; phone: string }) => {
    const response = await $http<{ access_token: string }>(`${API_ENDPOINTS.REGISTER}`, {
      method: "POST",
      body: credentials,
    });

    if (response.error) {
      notify('Signup failed', 'error');
      return { error: response.error };
    }

    if (response.data) {
      notify('Signup successful', 'success');
      return { data: response };
    }
    return { error: { message: "Invalid response from server" } };
  }

  const logout = async () => {
    try {
      await $http(`${API_ENDPOINTS.LOGOUT}`, { method: "POST" });
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      token.value = null;
      user.value = null;

      window.location.href = '/';
    }
  };

  const fetchCurrentUser = async () => {
    if (!token.value) {
      user.value = null;
      return;
    }

    try {
      const response = await $http(`${API_ENDPOINTS.CURRENT_USER}`);
      if (response.error) {
        user.value = null;
        token.value = null;
      } else {
        user.value = response.data as typeof user.value;
      }
    } catch (error) {
      console.error('Fetch user error:', error);
      user.value = null;
      token.value = null;
    }
  };

  const getCurrentUserId = (): number | null => {
    return user.value?.data?.user?.id || null;
  };

  return {
    user,
    token: readonly(token),
    signin,
    signup,
    logout,
    fetchCurrentUser,
    isAuthenticated: computed(() => !!token.value && !!user.value),
    getCurrentUserId
  };
};