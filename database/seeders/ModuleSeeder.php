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
                        'description' => 'Manage sales, marketing, and customer support with advanced CRM tools.',
                        'url' => '/crm',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Finance Management',
                        'description' => 'Comprehensive finance management for accounting, invoicing, and expenses.',
                        'url' => '/finance',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 29.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Inventory Management',
                        'description' => 'Track and manage inventory, orders, and suppliers with ease.',
                        'url' => '/inventory',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Workspace Collaboration',
                        'description' => 'Facilitate team collaboration with shared workspaces and communication tools.',
                        'url' => '/workspace',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 29.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Customer Interaction & Support',
                'slug' => 'customer-interaction-support',
                'description' => 'Manage customer interactions, support, and knowledge resources efficiently.',
                'modules' => [
                    [
                        'name' => 'Contact Center',
                        'description' => 'Interact with customers via social media, email, SMS, and calls in one place.',
                        'url' => '/contact-center',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 59.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Customer Support',
                        'description' => 'Handle customer support inquiries and provide excellent service.',
                        'url' => '/support',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 24.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Knowledge Base',
                        'description' => 'Create and manage a knowledge base for internal and external use.',
                        'url' => '/knowledge-base',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
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
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 39.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Appraisal & Performance Management',
                        'description' => 'Conduct staff performance appraisals and manage performance contracts.',
                        'url' => '/appraisal-performance',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Compliance',
                        'description' => 'Monitor and ensure organizational compliance with relevant regulations.',
                        'url' => '/compliance',
                        'banner' => '',
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
                        'description' => 'Efficiently manage projects, tasks, and teams with powerful management features.',
                        'url' => '/project-task-management',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 59.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Workflow Automation',
                        'description' => 'Automate and streamline your business processes and workflows.',
                        'url' => '/workflow-automation',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Sales & Marketing',
                'slug' => 'sales-marketing',
                'description' => 'Solutions to boost marketing efforts and drive sales with data-driven insights.',
                'modules' => [
                    [
                        'name' => 'Marketing & Campaign Management',
                        'description' => 'Automate marketing campaigns and manage campaigns effectively.',
                        'url' => '/marketing-campaign-management',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 44.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Sales & Lead Management',
                        'description' => 'Gain insights on sales performance and manage leads to maximize conversions.',
                        'url' => '/sales-lead-management',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Document Management',
                'slug' => 'document-management',
                'description' => 'Organize, store, and manage your documents and files efficiently.',
                'modules' => [
                    [
                        'name' => 'Document Storage & Management',
                        'description' => 'Organize, store, and manage your documents efficiently.',
                        'url' => '/documents',
                        'banner' => '',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 39.99,
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