<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CountryCode;
use Illuminate\Database\Seeder;

class CountryCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countryCodes = [
            ['name' => 'Bangladesh', 'code' => 'BD', 'dial_code' => '+88', 'status' => 1, 'sort_order' => 1],
            ['name' => 'United States', 'code' => 'US', 'dial_code' => '+1', 'status' => 1, 'sort_order' => 2],
            ['name' => 'Canada', 'code' => 'CA', 'dial_code' => '+1', 'status' => 1, 'sort_order' => 3],
            ['name' => 'United Kingdom', 'code' => 'GB', 'dial_code' => '+44', 'status' => 1, 'sort_order' => 4],
            ['name' => 'Australia', 'code' => 'AU', 'dial_code' => '+61', 'status' => 1, 'sort_order' => 5],
            ['name' => 'India', 'code' => 'IN', 'dial_code' => '+91', 'status' => 1, 'sort_order' => 6],
            ['name' => 'Germany', 'code' => 'DE', 'dial_code' => '+49', 'status' => 1, 'sort_order' => 7],
            ['name' => 'France', 'code' => 'FR', 'dial_code' => '+33', 'status' => 1, 'sort_order' => 8],
            ['name' => 'Japan', 'code' => 'JP', 'dial_code' => '+81', 'status' => 1, 'sort_order' => 9],
            ['name' => 'China', 'code' => 'CN', 'dial_code' => '+86', 'status' => 1, 'sort_order' => 10],
            ['name' => 'Brazil', 'code' => 'BR', 'dial_code' => '+55', 'status' => 1, 'sort_order' => 11],
            ['name' => 'Russia', 'code' => 'RU', 'dial_code' => '+7', 'status' => 1, 'sort_order' => 12],
            ['name' => 'South Africa', 'code' => 'ZA', 'dial_code' => '+27', 'status' => 1, 'sort_order' => 13],
            ['name' => 'Mexico', 'code' => 'MX', 'dial_code' => '+52', 'status' => 1, 'sort_order' => 14],
            ['name' => 'Italy', 'code' => 'IT', 'dial_code' => '+39', 'status' => 1, 'sort_order' => 15],
            ['name' => 'Spain', 'code' => 'ES', 'dial_code' => '+34', 'status' => 1, 'sort_order' => 16],
            ['name' => 'Turkey', 'code' => 'TR', 'dial_code' => '+90', 'status' => 1, 'sort_order' => 17],
            ['name' => 'Netherlands', 'code' => 'NL', 'dial_code' => '+31', 'status' => 1, 'sort_order' => 18],
            ['name' => 'Sweden', 'code' => 'SE', 'dial_code' => '+46', 'status' => 1, 'sort_order' => 19],
            ['name' => 'Denmark', 'code' => 'DK', 'dial_code' => '+45', 'status' => 1, 'sort_order' => 20],
            ['name' => 'Norway', 'code' => 'NO', 'dial_code' => '+47', 'status' => 1, 'sort_order' => 21],
            ['name' => 'Poland', 'code' => 'PL', 'dial_code' => '+48', 'status' => 1, 'sort_order' => 22],
            ['name' => 'Argentina', 'code' => 'AR', 'dial_code' => '+54', 'status' => 1, 'sort_order' => 23],
            ['name' => 'Chile', 'code' => 'CL', 'dial_code' => '+56', 'status' => 1, 'sort_order' => 24],
            ['name' => 'Colombia', 'code' => 'CO', 'dial_code' => '+57', 'status' => 1, 'sort_order' => 25],
            ['name' => 'Ecuador', 'code' => 'EC', 'dial_code' => '+593', 'status' => 1, 'sort_order' => 26],
            ['name' => 'Peru', 'code' => 'PE', 'dial_code' => '+51', 'status' => 1, 'sort_order' => 27],
            ['name' => 'Uruguay', 'code' => 'UY', 'dial_code' => '+598', 'status' => 1, 'sort_order' => 28],
            ['name' => 'Venezuela', 'code' => 'VE', 'dial_code' => '+58', 'status' => 1, 'sort_order' => 29],
            ['name' => 'Bolivia', 'code' => 'BO', 'dial_code' => '+591', 'status' => 1, 'sort_order' => 30],
            ['name' => 'Paraguay', 'code' => 'PY', 'dial_code' => '+595', 'status' => 1, 'sort_order' => 31],
            ['name' => 'Guyana', 'code' => 'GY', 'dial_code' => '+592', 'status' => 1, 'sort_order' => 32],
            ['name' => 'Suriname', 'code' => 'SR', 'dial_code' => '+597', 'status' => 1, 'sort_order' => 33],
            ['name' => 'Belize', 'code' => 'BZ', 'dial_code' => '+501', 'status' => 1, 'sort_order' => 34],
            ['name' => 'Costa Rica', 'code' => 'CR', 'dial_code' => '+506', 'status' => 1, 'sort_order' => 35],
            ['name' => 'El Salvador', 'code' => 'SV', 'dial_code' => '+503', 'status' => 1, 'sort_order' => 36],
            ['name' => 'Guatemala', 'code' => 'GT', 'dial_code' => '+502', 'status' => 1, 'sort_order' => 37],
            ['name' => 'Honduras', 'code' => 'HN', 'dial_code' => '+504', 'status' => 1, 'sort_order' => 38],
            ['name' => 'Nicaragua', 'code' => 'NI', 'dial_code' => '+505', 'status' => 1, 'sort_order' => 39],
            ['name' => 'Panama', 'code' => 'PA', 'dial_code' => '+507', 'status' => 1, 'sort_order' => 40],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'dial_code' => '+971', 'status' => 1, 'sort_order' => 41],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'dial_code' => '+966', 'status' => 1, 'sort_order' => 42],
            ['name' => 'Qatar', 'code' => 'QA', 'dial_code' => '+974', 'status' => 1, 'sort_order' => 43],
            ['name' => 'Kuwait', 'code' => 'KW', 'dial_code' => '+965', 'status' => 1, 'sort_order' => 44],
            ['name' => 'Oman', 'code' => 'OM', 'dial_code' => '+968', 'status' => 1, 'sort_order' => 45],
            ['name' => 'Bahrain', 'code' => 'BH', 'dial_code' => '+973', 'status' => 1, 'sort_order' => 46],
            ['name' => 'Jordan', 'code' => 'JO', 'dial_code' => '+962', 'status' => 1, 'sort_order' => 47],
            ['name' => 'Lebanon', 'code' => 'LB', 'dial_code' => '+961', 'status' => 1, 'sort_order' => 48],
            ['name' => 'Syria', 'code' => 'SY', 'dial_code' => '+963', 'status' => 1, 'sort_order' => 49],
            ['name' => 'Iraq', 'code' => 'IQ', 'dial_code' => '+964', 'status' => 1, 'sort_order' => 50],
            ['name' => 'Yemen', 'code' => 'YE', 'dial_code' => '+967', 'status' => 1, 'sort_order' => 51],
            ['name' => 'Tunisia', 'code' => 'TN', 'dial_code' => '+216', 'status' => 1, 'sort_order' => 52],
            ['name' => 'Libya', 'code' => 'LY', 'dial_code' => '+218', 'status' => 1, 'sort_order' => 53],
            ['name' => 'Algeria', 'code' => 'DZ', 'dial_code' => '+213', 'status' => 1, 'sort_order' => 54],
            ['name' => 'Morocco', 'code' => 'MA', 'dial_code' => '+212', 'status' => 1, 'sort_order' => 55],
            ['name' => 'Tanzania', 'code' => 'TZ', 'dial_code' => '+255', 'status' => 1, 'sort_order' => 56],
            ['name' => 'Uganda', 'code' => 'UG', 'dial_code' => '+256', 'status' => 1, 'sort_order' => 57],
            ['name' => 'Rwanda', 'code' => 'RW', 'dial_code' => '+250', 'status' => 1, 'sort_order' => 58],
            ['name' => 'Kenya', 'code' => 'KE', 'dial_code' => '+254', 'status' => 1, 'sort_order' => 59],
            ['name' => 'Nigeria', 'code' => 'NG', 'dial_code' => '+234', 'status' => 1, 'sort_order' => 60],
            ['name' => 'Ghana', 'code' => 'GH', 'dial_code' => '+233', 'status' => 1, 'sort_order' => 61],
            ['name' => 'Cameroon', 'code' => 'CM', 'dial_code' => '+237', 'status' => 1, 'sort_order' => 62],
            ['name' => 'Ivory Coast', 'code' => 'CI', 'dial_code' => '+225', 'status' => 1, 'sort_order' => 63],
            ['name' => 'Burkina Faso', 'code' => 'BF', 'dial_code' => '+226', 'status' => 1, 'sort_order' => 64],
            ['name' => 'Mali', 'code' => 'ML', 'dial_code' => '+223', 'status' => 1, 'sort_order' => 65],
            ['name' => 'Senegal', 'code' => 'SN', 'dial_code' => '+221', 'status' => 1, 'sort_order' => 66],
            ['name' => 'Guinea', 'code' => 'GN', 'dial_code' => '+224', 'status' => 1, 'sort_order' => 67],
            ['name' => 'Gambia', 'code' => 'GM', 'dial_code' => '+220', 'status' => 1, 'sort_order' => 68],
            ['name' => 'Sierra Leone', 'code' => 'SL', 'dial_code' => '+232', 'status' => 1, 'sort_order' => 69],
            ['name' => 'Liberia', 'code' => 'LR', 'dial_code' => '+231', 'status' => 1, 'sort_order' => 70],
            ['name' => 'Mauritania', 'code' => 'MR', 'dial_code' => '+222', 'status' => 1, 'sort_order' => 71],
            ['name' => 'Mozambique', 'code' => 'MZ', 'dial_code' => '+258', 'status' => 1, 'sort_order' => 72],
            ['name' => 'Zambia', 'code' => 'ZM', 'dial_code' => '+260', 'status' => 1, 'sort_order' => 73],
            ['name' => 'Zimbabwe', 'code' => 'ZW', 'dial_code' => '+263', 'status' => 1, 'sort_order' => 74],
            ['name' => 'Namibia', 'code' => 'NA', 'dial_code' => '+264', 'status' => 1, 'sort_order' => 75],
            ['name' => 'Botswana', 'code' => 'BW', 'dial_code' => '+267', 'status' => 1, 'sort_order' => 76],
            ['name' => 'Lesotho', 'code' => 'LS', 'dial_code' => '+266', 'status' => 1, 'sort_order' => 77],
            ['name' => 'Eswatini', 'code' => 'SZ', 'dial_code' => '+268', 'status' => 1, 'sort_order' => 78],
            ['name' => 'South Africa', 'code' => 'ZA', 'dial_code' => '+27', 'status' => 1, 'sort_order' => 79],
            ['name' => 'Eritrea', 'code' => 'ER', 'dial_code' => '+291', 'status' => 1, 'sort_order' => 80],
            ['name' => 'Ethiopia', 'code' => 'ET', 'dial_code' => '+251', 'status' => 1, 'sort_order' => 81],
            ['name' => 'Djibouti', 'code' => 'DJ', 'dial_code' => '+253', 'status' => 1, 'sort_order' => 82],
            ['name' => 'Somalia', 'code' => 'SO', 'dial_code' => '+252', 'status' => 1, 'sort_order' => 83],
            ['name' => 'Kenya', 'code' => 'KE', 'dial_code' => '+254', 'status' => 1, 'sort_order' => 84],
            ['name' => 'Tanzania', 'code' => 'TZ', 'dial_code' => '+255', 'status' => 1, 'sort_order' => 85],
            ['name' => 'Uganda', 'code' => 'UG', 'dial_code' => '+256', 'status' => 1, 'sort_order' => 86],
            ['name' => 'Rwanda', 'code' => 'RW', 'dial_code' => '+250', 'status' => 1, 'sort_order' => 87],
            ['name' => 'Burundi', 'code' => 'BI', 'dial_code' => '+257', 'status' => 1, 'sort_order' => 88],
            
        ];

        foreach ($countryCodes as $code) {
            CountryCode::create($code);
        }
    }
}