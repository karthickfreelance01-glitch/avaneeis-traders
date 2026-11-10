<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'AVANEEIS TRADERS',
            'company_tagline' => 'Quality Equipment • Better Farming • Higher Returns',
            'company_description' => 'AVANEEIS TRADERS supplies modern poultry farm equipment for broiler, layer, and breeder farms.',
            'contact_phone_1' => '98423 24074',
            'contact_phone_2' => '98406 37950',
            'whatsapp_number' => '94999 53094',
            'email' => 'avaneeistraders@gmail.com',
            'gstin' => '33AXKPR9045B1ZF',
            'head_office' => '#39, Deivasigamani Nagar, M.M Avenue, Kanchipuram – 631502, Tamil Nadu, India.',
            'branch_office' => 'Periyanayagi Amman Complex, 43-G/9, Avinashi Road, Thottipalayam Pirivu, Coimbatore – 641014, Tamil Nadu, India.',
            'warehouse_address' => 'KRS Building, Elangadu Village, Vandavasi, Tiruvannamalai District – 604408, Tamil Nadu, India.',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'linkedin_url' => '#',
            'footer_content' => 'Complete poultry farm equipment solutions for broiler, layer and breeder operations.',
            'about_us' => 'Since 2018, we have helped poultry farmers upgrade their operations with reliable equipment, practical automation, and strong service support.',
            'mission' => 'To support poultry farmers with efficient, durable and affordable equipment that improves productivity and profitability.',
            'vision' => 'To become the most trusted partner for modern poultry farm automation across India.',
        ];

        foreach ($settings as $key => $value) {
            CompanySetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
    }
}
