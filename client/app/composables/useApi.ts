import { services } from '../services'

export const useApi = () => {
  return {
    auth: services.auth,
    user: services.user,
    role: services.role,
    permission: services.permission,
    localization: services.localization
  }
}