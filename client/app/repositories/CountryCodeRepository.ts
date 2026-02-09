import type { ApiResponse } from "~/api/types/api.types";
import { BaseRepository } from "./BaseRepository";

interface CountryCode {
  id: number;
  code: string;
  name: string;
  dial_code: string;
}

export class CountryCodeRepository extends BaseRepository {
  constructor() {
    super()
  }
    async getAll(): Promise<ApiResponse<CountryCode[]>> {
      return this.get<CountryCode[]>('/countries')
    }
}