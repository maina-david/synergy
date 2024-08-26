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
                'name' => 'Business Management',
                'slug' => 'business-management',
                'description' => 'Tools and services that help manage and automate business operations.',
                'modules' => [
                    [
                        'name' => 'CRM',
                        'description' => 'Manage sales, marketing, and customer support with advanced CRM tools.',
                        'url' => '/crm',
                        'banner' => 'https://media.istockphoto.com/id/1909627240/photo/customer-experience-concept-best-excellent-services-rating-for-satisfaction-present-by-hand.jpg?s=2048x2048&w=is&k=20&c=zsiX8ApwrrU9TT1_kciMsunGHtPAwOPwG3G5j5hPUwk=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Finance',
                        'description' => 'Comprehensive finance management for accounting, invoicing, and expenses.',
                        'url' => '/finance',
                        'banner' => 'https://media.istockphoto.com/id/1795167728/photo/growth-in-business-and-finance-growing-graphs-and-charts.jpg?s=2048x2048&w=is&k=20&c=5hv0jmjGD-S2xo3h8jLxAimVgfirVEpQxQp7igHTd4E=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 29.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Inventory Management',
                        'description' => 'Track and manage inventory, orders, and suppliers with ease.',
                        'url' => '/inventory',
                        'banner' => 'https://media.istockphoto.com/id/1484852942/photo/smart-warehouse-inventory-management-system-concept.jpg?s=1024x1024&w=is&k=20&c=fvpl9xGLIpIj9mliO0em0iOzYlRQEIojUfQt3kIEFxk=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 34.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Human Resources',
                'slug' => 'human-resources',
                'description' => 'Solutions to streamline HR management, including employee and payroll services.',
                'modules' => [
                    [
                        'name' => 'HR Management',
                        'description' => 'Streamline HR functions including employee management, payroll, and recruitment.',
                        'url' => '/hr',
                        'banner' => 'https://media.istockphoto.com/id/1185204809/photo/team-corporate-responisibility.jpg?s=2048x2048&w=is&k=20&c=c3fvX7chIzTrLjXPz47GMpPf7cf5upkKC_q1yXtz0UY=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 39.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Project and Workflow',
                'slug' => 'project-workflow',
                'description' => 'Tools for project management and workflow automation.',
                'modules' => [
                    [
                        'name' => 'Project Management',
                        'description' => 'Efficiently manage projects, tasks, and teams with powerful project management features.',
                        'url' => '/projects',
                        'banner' => 'https://plus.unsplash.com/premium_photo-1661782562303-b6839db30206?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 59.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Workflow Automation',
                        'description' => 'Automate and streamline your business processes and workflows.',
                        'url' => '/workflow-automation',
                        'banner' => 'https://media.istockphoto.com/id/1287248836/photo/business-process-flowchart.jpg?s=1024x1024&w=is&k=20&c=JsN9Wn1bdArEQHw6CItvWFJL0rkQi-GAbDwOFDvEbXA=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 49.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Sales and Marketing',
                'slug' => 'sales-marketing',
                'description' => 'Solutions for marketing automation, sales analytics, and customer engagement.',
                'modules' => [
                    [
                        'name' => 'Marketing Automation',
                        'description' => 'Automate marketing campaigns and track performance to boost engagement.',
                        'url' => '/marketing',
                        'banner' => 'https://media.istockphoto.com/id/1367967285/photo/automation-software-technology-process-system-business-concept.jpg?s=1024x1024&w=is&k=20&c=4FOdgCZQ3c_xwY3jkLn7RoAclq_NHxdtXJK9D2xDVas=',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 44.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Sales Analytics',
                        'description' => 'Gain insights and analytics on sales performance and trends.',
                        'url' => '/sales-analytics',
                        'banner' => 'https://plus.unsplash.com/premium_photo-1700675175407-53c53101cbb8?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 54.99,
                        'active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Support and Documents',
                'slug' => 'support-documents',
                'description' => 'Solutions for customer support and document management.',
                'modules' => [
                    [
                        'name' => 'Customer Support',
                        'description' => 'Handle customer support inquiries and provide excellent service.',
                        'url' => '/support',
                        'banner' => 'https://plus.unsplash.com/premium_photo-1661434914660-c68d9fd54753?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'subscription_type' => SubscriptionType::MONTHLY,
                        'price' => 24.99,
                        'active' => true,
                    ],
                    [
                        'name' => 'Document Management',
                        'description' => 'Organize, store, and manage your documents and files efficiently.',
                        'url' => '/documents',
                        'banner' => 'https://plus.unsplash.com/premium_photo-1669658981976-4b72e927a902?q=80&w=2153&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
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