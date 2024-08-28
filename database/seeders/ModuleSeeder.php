<?php

namespace Database\Seeders;

use App\Enums\Billing\Subscription\SubscriptionType;
use Illuminate\Database\Seeder;
use App\Models\Administration\Module;
use App\Models\Administration\ModuleCategory;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleCategories = [
            [
                'name' => 'Business Operations',
                'slug' => 'business-operations',
                'description' => 'Tools for managing core business functions like CRM, finance, inventory, and more.',
                'modules' => [
                    [
                        'name' => 'CRM',
                        'description' => 'Manage sales, marketing, and customer support.',
                        'url' => '/crm',
                        'banner' => 'https://media.istockphoto.com/id/1471444483/photo/customer-satisfaction-survey-concept-users-rate-service-experiences-on-online-application.webp?b=1&s=612x612&w=0&k=20&c=2Wtg2ur5qT3ZFazgxIJYmkPD1ds8p_IVMmrABjZ4NOM=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Inventory Management',
                        'description' => 'Track and manage inventory, orders, and suppliers with ease.',
                        'url' => '/inventory',
                        'banner' => 'https://media.istockphoto.com/id/527045000/photo/female-manager-working-on-tablet-in-factory.webp?b=1&s=612x612&w=0&k=20&c=aR7fKINePAyHs4X4ISNCet8_rwpdpZq7i6BjtODJXLU=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Contact Center',
                        'description' => 'Manage voice, email, and social media communications in one place.',
                        'url' => '/contact-center',
                        'banner' => 'https://plus.unsplash.com/premium_photo-1661407488345-835fdc71bf06?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 29.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Human Resources & Compliance',
                'slug' => 'hr-compliance',
                'description' => 'Solutions to streamline HR management, ensure compliance, and optimize employee performance.',
                'modules' => [
                    [
                        'name' => 'HR Management',
                        'description' => 'Streamline HR functions including employee management, payroll, and recruitment.',
                        'url' => '/hr',
                        'banner' => 'https://media.istockphoto.com/id/1185204809/photo/team-corporate-responisibility.webp?b=1&s=612x612&w=0&k=20&c=mUzhZopcpAaUUvHRRsMfr5xBxiFCnYUvbxwDD0HN6U0=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 39.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Appraisal & Performance Management',
                        'description' => 'Conduct staff performance appraisals and manage performance contracts.',
                        'url' => '/performance-management',
                        'banner' => 'https://media.istockphoto.com/id/1550901370/photo/magnifying-success-powerful-red-wooden-businessman-leading-way-human-resource-empowering.webp?b=1&s=612x612&w=0&k=20&c=ppXiHawggKrTUB5S6CvFp0dHcRuKGn8gtxdo_bgAG74=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Compliance Management',
                        'description' => 'Monitor and ensure organizational compliance with relevant regulations.',
                        'url' => '/compliance-management',
                        'banner' => 'https://media.istockphoto.com/id/1181218189/photo/compliance-concept-with-wooden-blocks-in-red-color.webp?b=1&s=612x612&w=0&k=20&c=IGPXDGc04S2euHc9cT4pXDXOiiqYNrkUzubQljE9LTs=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 39.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Project & Workflow Management',
                'slug' => 'project-workflow-management',
                'description' => 'Advanced tools for efficient project management and workflow automation.',
                'modules' => [
                    [
                        'name' => 'Project & Task Management',
                        'description' => 'Efficiently manage projects, tasks, and teams.',
                        'url' => '/project-task-management',
                        'banner' => 'https://media.istockphoto.com/id/1714483761/photo/business-management-kanban-training.webp?b=1&s=612x612&w=0&k=20&c=v3d59AZBUUweW5fVEqqA3FmrHcKQ08iexsklDLWiOOQ=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 59.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Workflow Automation',
                        'description' => 'Automate and streamline your business processes.',
                        'url' => '/workflow-automation',
                        'banner' => 'https://media.istockphoto.com/id/1415348384/photo/process-analyzing-solution-strategy-process-workflow-proceeding-business.webp?b=1&s=612x612&w=0&k=20&c=F6lMekClFOEmJuNCEWit9MaMzQXDlmfbOkNep1S54mA=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                ],
            ],
        ];

        DB::transaction(function () use ($moduleCategories) {
            foreach ($moduleCategories as $moduleCategoryData) {
                $moduleCategory = ModuleCategory::create([
                    'name' => $moduleCategoryData['name'],
                    'slug' => $moduleCategoryData['slug'],
                    'description' => $moduleCategoryData['description'],
                ]);

                foreach ($moduleCategoryData['modules'] as $moduleData) {
                    Module::factory()->create([
                        'name' => $moduleData['name'],
                        'description' => $moduleData['description'],
                        'url' => $moduleData['url'],
                        'banner' => $moduleData['banner'],
                        'subscription_type' => $moduleData['subscription_type'],
                        'price' => $moduleData['price'],
                        'active' => $moduleData['active'],
                        'module_category_id' => $moduleCategory->id,
                    ]);
                }
            }
        });
    }
}