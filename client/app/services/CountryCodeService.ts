import { CountryCodeRepository } from "~/repositories/CountryCodeRepository"

export class CountryCodeService {
  private repository: CountryCodeRepository

  constructor() {
    this.repository = new CountryCodeRepository()
  }

  async getAllCountryCodes() {
    const response = await this.repository.getAll()
    return response
  }
}