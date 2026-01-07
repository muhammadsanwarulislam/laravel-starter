import { AuthService } from './AuthService'
import { UserService } from './UserService'
import { RoleService } from './RoleService'
import { PermissionService } from './PermissionService'
import { LocalizationService } from './LocalizationService'

export class ServiceFactory {
  private static instances: Map<string, any> = new Map()

  static getService<T>(ServiceClass: new () => T): T {
    const className = ServiceClass.name
    if (!this.instances.has(className)) {
      this.instances.set(className, new ServiceClass())
    }
    return this.instances.get(className)
  }

  // Singleton getters
  static get auth(): AuthService {
    return this.getService(AuthService)
  }

  static get user(): UserService {
    return this.getService(UserService)
  }

  static get role(): RoleService {
    return this.getService(RoleService)
  }

  static get permission(): PermissionService {
    return this.getService(PermissionService)
  }

  static get localization(): LocalizationService {
    return this.getService(LocalizationService)
  }

  static clearAll(): void {
    this.instances.clear()
  }

  static getAllServices(): string[] {
    return Array.from(this.instances.keys())
  }
}

export const services = ServiceFactory
