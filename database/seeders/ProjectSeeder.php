<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectPanel;
use App\Models\TechTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->projects() as $index => $data) {
            $project = Project::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'client_name' => $data['client'] ?? null,
                    'category' => $data['category'],
                    'summary' => $data['summary'],
                    'problem' => $data['problem'],
                    'solution' => $data['solution'],
                    'outcome' => $data['outcome'],
                    'highlights' => $data['highlights'],
                    'live_url' => $data['live_url'],
                    'year' => $data['year'],
                    'role' => $data['role'],
                    'status' => 'published',
                    'is_featured' => $data['featured'],
                    'position' => $index + 1,
                    'seo_title' => $data['title'].' — Case Study | Fahad Jadiya',
                    'seo_description' => Str::limit($data['summary'], 155),
                ],
            );

            // Tech tags (create-if-missing, ordered).
            $sync = [];
            foreach ($data['tech'] as $pos => $name) {
                $tag = TechTag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
                $sync[$tag->id] = ['position' => $pos + 1];
            }
            $project->techTags()->sync($sync);

            // Rebuild panels + features idempotently (cascade deletes features).
            $project->panels()->delete();
            foreach ($data['panels'] as $panelPos => $panel) {
                $panelModel = ProjectPanel::create([
                    'project_id' => $project->id,
                    'name' => $panel['name'],
                    'icon' => $panel['icon'] ?? null,
                    'description' => $panel['description'] ?? null,
                    'position' => $panelPos + 1,
                ]);

                foreach ($panel['features'] as $featurePos => $feature) {
                    $panelModel->features()->create([
                        'project_id' => $project->id,
                        'title' => $feature,
                        'position' => $featurePos + 1,
                    ]);
                }
            }
        }
    }

    private function projects(): array
    {
        return [
            [
                'slug' => 'garba-flow',
                'title' => 'Garba Flow — Garba Class Management',
                'client' => 'Burning Foots',
                'category' => 'SaaS / Management System',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://burningfoots.com',
                'featured' => true,
                'summary' => 'Multi-branch SaaS to manage Garba dance classes — students, workshops, attendance, fees and reports with WhatsApp notifications and public QR self-registration.',
                'problem' => 'A growing Garba academy needed to manage students, memberships, attendance and fee collection across multiple branches, replacing manual registers and spreadsheets.',
                'solution' => 'A Laravel 12 + Inertia + Vue SaaS with role-based access, calendar-accurate membership math, receipt generation, reporting dashboards and shareable QR registration links.',
                'outcome' => 'Streamlined enrollment, automated fee receipts and reminders, and gave admins real-time revenue and attendance analytics across all branches.',
                'highlights' => [
                    'Multi-branch architecture with capacity enforcement',
                    'Membership auto-calculation with calendar-accurate date math',
                    'Public QR self-registration and shareable HTML receipts/ID cards',
                    'WhatsApp notifications and CSV exports',
                    '10+ chart analytics with month-over-month and year-over-year comparisons',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'Tailwind CSS', 'MySQL', 'PHP'],
                'panels' => [
                    ['name' => 'Super Admin', 'icon' => 'shield', 'description' => 'Full control over branches, users and the public website CMS.', 'features' => [
                        'Branch CRUD with capacity limits and delete-guards',
                        'User management (super admin / staff / viewer) with per-module permissions',
                        'Website CMS — branding, gallery, reviews, locations and batch timings',
                    ]],
                    ['name' => 'Students', 'icon' => 'users', 'description' => 'End-to-end student lifecycle management.', 'features' => [
                        'Registration with photo upload and mobile validation',
                        'Membership auto-calculation, renewals and history',
                        'Bulk import and operations via CSV',
                        'Printable, shareable ID cards',
                    ]],
                    ['name' => 'Workshops', 'icon' => 'calendar', 'description' => 'Event creation, registration and conversion.', 'features' => [
                        'Multi-date free/paid events with UPI QR codes',
                        'Attendance tracking per attendee',
                        'Promote attendees to full students',
                        'QR-shareable registration links',
                    ]],
                    ['name' => 'Fees', 'icon' => 'receipt', 'description' => 'Payment recording and receipts.', 'features' => [
                        'Payment recording (cash / UPI / online) with statuses',
                        'Receipt file uploads',
                        'Branded, shareable HTML receipts',
                        'CSV export with Excel-safe formatting',
                    ]],
                    ['name' => 'Attendance', 'icon' => 'check-square', 'description' => 'Daily, duplicate-proof attendance.', 'features' => [
                        'Per-batch daily marking',
                        'Duplicate-proof upserts',
                        'Optional WhatsApp notifications',
                    ]],
                    ['name' => 'Reports & Dashboard', 'icon' => 'bar-chart', 'description' => 'Analytics and exports.', 'features' => [
                        'Branch-filtered KPIs and revenue/expense charts',
                        'Expiring-soon membership alerts',
                        'CSV exports across modules',
                    ]],
                    ['name' => 'Public Site', 'icon' => 'globe', 'description' => 'Marketing and self-service.', 'features' => [
                        'Dynamic landing (gallery, reviews, events)',
                        'QR / link self-registration',
                        'Public receipts and ID cards',
                        'JSON-LD structured data for SEO',
                    ]],
                ],
            ],
            [
                'slug' => 'autoconsult-dealer',
                'title' => 'AutoConsult — Automotive Marketplace & Dealer CRM',
                'client' => 'AutoConsult',
                'category' => 'SaaS / Marketplace + CRM',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://autoconsult.fahad-jadiya.com',
                'featured' => true,
                'summary' => 'AI-powered automotive marketplace combining a public vehicle marketplace, an 11-stage dealer CRM, subscription billing and regional notifications.',
                'problem' => 'Used-car dealers needed a single platform to list inventory, capture buyer inquiries and manage the sales pipeline — while buyers needed trustworthy discovery and pricing.',
                'solution' => 'A Laravel 12 + Inertia + Vue (TypeScript) platform with Spatie RBAC, a Kanban lead pipeline, AI pricing/fraud heuristics, payments and white-label dealer portals.',
                'outcome' => 'Gave dealers a full lead-to-close CRM with scoring and reporting, and buyers an AI-assisted marketplace with price insights and side-by-side comparison.',
                'highlights' => [
                    '7 roles with Spatie RBAC and activity logging',
                    '11-stage Kanban lead pipeline with lead scoring',
                    'AI price estimation, fraud detection and description generator',
                    'Subscription billing (Stripe / Razorpay) and an ads platform',
                    'Provider-swappable service stubs for SMS, payments, OAuth and AI',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'MySQL', 'Spatie Permission', 'Stripe', 'Razorpay'],
                'panels' => [
                    ['name' => 'Public Marketplace', 'icon' => 'car', 'description' => 'Discovery for buyers.', 'features' => [
                        'Advanced search and filters',
                        'Vehicle detail with specs, condition and price history',
                        'AI shopping assistant and voice search',
                        'Inquiry submission and vehicle comparison',
                    ]],
                    ['name' => 'Seller / Dealer Listing', 'icon' => 'tag', 'description' => 'Inventory and inquiries.', 'features' => [
                        'Full vehicle listing CRUD with media and watermarking',
                        'AI description and price suggestions',
                        'Inquiry inbox and in-app chat',
                    ]],
                    ['name' => 'Dealer CRM', 'icon' => 'kanban', 'description' => 'Lead-to-close pipeline.', 'features' => [
                        '11-stage Kanban pipeline',
                        'Lead scoring, assignment and activity logs',
                        'Appointments and test drives',
                        'Sales reports — funnel, conversion, top performers',
                        'Team management and white-label branding',
                    ]],
                    ['name' => 'Super Admin', 'icon' => 'shield', 'description' => 'Platform governance.', 'features' => [
                        'Business-intelligence dashboard',
                        'User and vehicle moderation',
                        'Dealer KYC verification',
                        'Subscription plans, ads, regions and API tokens',
                    ]],
                ],
            ],
            [
                'slug' => 'b2b-dealer',
                'title' => 'B2B Dealer — Auto-Parts Wholesale Platform',
                'client' => 'B2B Dealer',
                'category' => 'B2B E-commerce',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://b2b-dealer.fahad-jadiya.com',
                'featured' => false,
                'summary' => 'A B2B wholesale platform with a 3-tier dynamic pricing engine, personalized dealer catalogs, carts and a multi-stage order workflow.',
                'problem' => 'A parts wholesaler needed dealer-specific pricing and a streamlined ordering process to replace ad-hoc phone and WhatsApp orders.',
                'solution' => 'Laravel 12 + Vue 3 (Pinia) with a priority-based pricing engine (fixed → category % → global %), an order state machine and analytics.',
                'outcome' => 'Each dealer sees personalized prices, places orders online with min-order enforcement, and admins manage pricing and fulfillment from one dashboard.',
                'highlights' => [
                    '3-tier dynamic pricing engine with batch resolution',
                    'Hierarchical catalog with vehicle compatibility',
                    'Order state machine with a reconfirmation step',
                    'Hybrid Sanctum stateful/stateless authentication',
                    'AmCharts analytics and PDF brochures',
                ],
                'tech' => ['Laravel', 'Vue.js', 'Pinia', 'Tailwind CSS', 'MySQL', 'Laravel Sanctum', 'AmCharts'],
                'panels' => [
                    ['name' => 'Admin', 'icon' => 'settings', 'description' => 'Catalog, pricing and orders.', 'features' => [
                        'Catalog (categories, brands, compatibility)',
                        '3-tier pricing and bulk price tools',
                        'Dealer management',
                        'Order workflow and notices',
                    ]],
                    ['name' => 'Dealer', 'icon' => 'shopping-cart', 'description' => 'Ordering with personalized pricing.', 'features' => [
                        'Personalized catalog pricing',
                        'Cart with minimum-order enforcement',
                        'Order placement and history',
                        'Reconfirmation responses',
                    ]],
                    ['name' => 'Guest', 'icon' => 'globe', 'description' => 'Public discovery.', 'features' => [
                        'Public catalog (base prices)',
                        'Downloadable brochure PDF',
                        'Inquiry form',
                        'Trust stats',
                    ]],
                ],
            ],
            [
                'slug' => 'business-point',
                'title' => 'Business Point — B2B Marketplace & Community Platform',
                'client' => 'DigitalBasu',
                'category' => 'Marketplace / Community',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://digitalbasu.in',
                'featured' => false,
                'summary' => 'A multi-role marketplace and community directory where users browse vendors, manage contact directories and register businesses (shop / doctor / barber).',
                'problem' => 'A local community needed a digital hub for businesses, services, classifieds, jobs and an emergency/contact directory.',
                'solution' => 'Laravel 12 + Vue 3 (Inertia) with dual auth (JWT users + session admin), dynamic RBAC, a polymorphic media system and audit logging.',
                'outcome' => 'A governed ecosystem where vendors self-register, users browse and post listings, and admins moderate everything with full audit trails.',
                'highlights' => [
                    'Dual auth: Sanctum/JWT users plus session-based admin',
                    'Dynamic RBAC (45+ permissions across 7 groups)',
                    'Polymorphic media system across all entities',
                    'Comprehensive audit logging via observers',
                    'Fast2SMS OTP and Google OAuth',
                ],
                'tech' => ['Laravel', 'Vue.js', 'Inertia.js', 'Pinia', 'Tailwind CSS', 'MySQL', 'Laravel Sanctum'],
                'panels' => [
                    ['name' => 'Super Admin', 'icon' => 'shield', 'description' => 'System-wide governance.', 'features' => [
                        'System config and maintenance mode',
                        'Dynamic RBAC and role management',
                        'Donation management',
                        'Sudo-admin creation',
                    ]],
                    ['name' => 'Admin', 'icon' => 'sliders', 'description' => 'Module moderation.', 'features' => [
                        'Vendor approval and management',
                        'Users, announcements and banners',
                        'Community directory and emergency contacts',
                        'Buy/sell and jobs moderation with audit logs',
                    ]],
                    ['name' => 'Vendor', 'icon' => 'store', 'description' => 'Business self-service.', 'features' => [
                        'Multi-type business (shop / doctor / barber)',
                        'Products / services and opening hours',
                        'Media and promotions',
                    ]],
                    ['name' => 'User', 'icon' => 'user', 'description' => 'Community participation.', 'features' => [
                        'Contact directory (public/private, tags, export)',
                        'Marketplace browsing',
                        'Buy/sell listings and job posts',
                        'OTP and Google authentication',
                    ]],
                ],
            ],
            [
                'slug' => 'ntz-fitness-gym-management',
                'title' => 'NTZ Fitness — Gym Management System',
                'client' => 'NTZ Fitness',
                'category' => 'SaaS / Management System',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'http://ntzfitness.fahad-jadiya.com',
                'featured' => true,
                'summary' => 'A gym management SaaS for members, subscriptions, payments and ID cards, with automated renewal/dues/birthday reminders and financial reporting.',
                'problem' => 'A fitness studio needed to manage memberships, renewals, payments and member engagement beyond spreadsheets.',
                'solution' => 'Laravel 12 + Inertia + Vue (TypeScript) with Spatie roles, atomic member/subscription/payment transactions, PDF receipts and ID cards, and templated notifications.',
                'outcome' => 'Automated the full membership lifecycle with dues tracking, bulk ID-card PDFs and daily/monthly/yearly payment reports.',
                'highlights' => [
                    'Atomic member + subscription + payment transactions',
                    'Auto receipts and bulk CR80 ID-card PDFs',
                    'Templated expiry / dues / birthday notifications',
                    'Payment reports (daily/monthly/yearly) with Excel export',
                    'Offers and coupons with usage limits',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'MySQL', 'Spatie Permission', 'DomPDF', 'Laravel Excel', 'Intervention Image'],
                'panels' => [
                    ['name' => 'Admin', 'icon' => 'shield', 'description' => 'Configuration and CMS.', 'features' => [
                        'Dashboard with 6 KPIs and trend charts',
                        'CMS (home / about / site settings)',
                        'Auto-notification triggers',
                        'ID-card designer and bulk PDF generation',
                    ]],
                    ['name' => 'Staff', 'icon' => 'clipboard', 'description' => 'Daily operations.', 'features' => [
                        'Member lifecycle (auto code, subscriptions, renewals)',
                        'Payments, auto-receipts and a dues tracker',
                        'Diet and workout plan assignment',
                        'Content CMS, notices, enquiries and offers',
                    ]],
                    ['name' => 'Member', 'icon' => 'user', 'description' => 'Self-service portal.', 'features' => [
                        'Subscription timeline and status',
                        'Payment history and receipts',
                        'Assigned plans and notices',
                        'ID-card download and profile',
                    ]],
                ],
            ],
            [
                'slug' => 'edusphere-school-management',
                'title' => 'EduSphere — Multi-Tenant School Management',
                'client' => 'EduSphere',
                'category' => 'SaaS / Multi-Tenant',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'http://school.fahad-jadiya.com',
                'featured' => true,
                'summary' => 'A multi-tenant school management SaaS with 10 roles and 140+ permissions covering academics, finance, HR, library and transport.',
                'problem' => 'Schools needed a single, secure platform to manage admissions, academics, staff, finances and parent communication with strict data isolation.',
                'solution' => 'Laravel 12 starter-kit base with single-database multi-tenancy (global scopes + tenant resolver), Spatie school-scoped teams, audit logging and a module generator.',
                'outcome' => 'A scalable, compliance-ready platform where each school\'s data is isolated and new feature modules can be scaffolded in seconds.',
                'highlights' => [
                    'Single-database multi-tenancy via global scopes and a resolver',
                    'School-scoped RBAC (Spatie teams) with 140+ permissions',
                    'Audit logging with before/after values and login history',
                    'make:module generator (model → tests) plus Docker and CI',
                    'Clean layered architecture (service / repository / policy)',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'MySQL', 'Redis', 'Spatie Permission', 'Docker'],
                'panels' => [
                    ['name' => 'Super Admin / Principal', 'icon' => 'shield', 'description' => 'Tenant and user control.', 'features' => [
                        'Multi-school tenancy and switching',
                        'User and role management',
                        'Audit logs',
                    ]],
                    ['name' => 'Teacher', 'icon' => 'book-open', 'description' => 'Academics and assessment.', 'features' => [
                        'Attendance, grading and report cards',
                        'Assignments and homework',
                        'Online exams with auto-grading',
                        'Timetable and question bank',
                    ]],
                    ['name' => 'Accountant / HR / Librarian / Transport', 'icon' => 'briefcase', 'description' => 'Operations and finance.', 'features' => [
                        'Fees, payroll and income/expense',
                        'Leave approvals and staff directory',
                        'Library catalogue and lending',
                        'Transport routes and vehicles',
                    ]],
                    ['name' => 'Student & Parent', 'icon' => 'users', 'description' => 'Portals for learners and guardians.', 'features' => [
                        'Exams, assignments and records',
                        'Multi-child parent dashboard',
                        'Attendance, performance and fees',
                        'Notices and communication',
                    ]],
                ],
            ],
            [
                'slug' => 'wecare-ortho',
                'title' => 'WeCare — Orthopaedic Clinic & AI Decision Support',
                'client' => 'WeCare Ortho',
                'category' => 'Healthcare / AI',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'http://wecare.fahad-jadiya.com',
                'featured' => true,
                'summary' => 'An orthopaedic clinic management system with patient records, rich visit/treatment entry, an AI treatment-suggestion engine and an agentic AI chat assistant.',
                'problem' => 'A clinic needed efficient patient and visit management plus decision support, while preserving its existing legacy database.',
                'solution' => 'Laravel 12 + Inertia + Vue (TypeScript) with multi-guard auth, a multi-provider AI client, treatment suggestions grounded on historical visits, and tool-calling chat.',
                'outcome' => 'Faster visit documentation, AI-grounded treatment suggestions, and an agentic assistant that can read and (with confirmation) write patient data.',
                'highlights' => [
                    'Multi-provider AI client (Groq / OpenRouter / Gemini / Mistral …)',
                    'Treatment suggestions grounded on the last 400 visits',
                    'Agentic AI chat with read/write tools and confirmation gates',
                    'Legacy schema modernized behind Eloquent accessors',
                    'Multi-guard authentication (doctor / receptionist)',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'TypeScript', 'shadcn-vue', 'Tailwind CSS', 'MySQL', 'Groq / LLaMA 3.3', 'OpenAI-compatible API'],
                'panels' => [
                    ['name' => 'Public', 'icon' => 'globe', 'description' => 'Marketing and contact.', 'features' => [
                        'Services, surgeon profile and blog',
                        'Gallery',
                        'Throttled contact form',
                        'SEO and XML sitemap',
                    ]],
                    ['name' => 'Doctor', 'icon' => 'stethoscope', 'description' => 'Clinical workflow with AI.', 'features' => [
                        'Patient management with duplicate prevention',
                        'Rich visit and treatment entry',
                        'Master-data CRUD (10+ clinical lookups)',
                        'AI treatment suggestions and agentic chat',
                        'Reports and contact inbox',
                    ]],
                    ['name' => 'Receptionist', 'icon' => 'clipboard', 'description' => 'Front-desk operations.', 'features' => [
                        'Patient registration',
                        'Search',
                        'Visit creation (scoped access)',
                    ]],
                ],
            ],
            [
                'slug' => 'fine-aluminium-invoice',
                'title' => 'Fine Aluminium — Invoicing & Billing System',
                'client' => 'Fine Aluminium',
                'category' => 'Invoicing / Billing',
                'year' => 2025,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://fine-aluminium.fahad-jadiya.com',
                'featured' => false,
                'summary' => 'A CodeIgniter 4 invoicing system for an aluminium business with GST, financial-year reporting and per-square-foot tiered pricing.',
                'problem' => 'An aluminium fabricator needed quotations and invoices with dimension-based pricing, GST and financial-year revenue tracking.',
                'solution' => 'A CodeIgniter 4 app with a sqft-tier pricing lookup, GST/discount/kasar handling, printable invoices and AmCharts analytics.',
                'outcome' => 'Sales staff generate accurate quotations with auto-filled per-sqft pricing, and owners see financial-year-scoped revenue across parties.',
                'highlights' => [
                    'Indian financial-year (Apr–Mar) scoped analytics',
                    'Per-square-foot tiered pricing auto-fill',
                    'GST (+18%), discount and kasar (rounding) handling',
                    'Multi-row line items (locations × products × sizes)',
                    'AmCharts dashboards and PDF/print invoices',
                ],
                'tech' => ['CodeIgniter 4', 'PHP', 'MySQL', 'AmCharts', 'Bootstrap'],
                'panels' => [
                    ['name' => 'Dashboard', 'icon' => 'bar-chart', 'description' => 'Financial overview.', 'features' => [
                        'Financial-year-scoped revenue KPIs',
                        'Monthly / yearly / party-wise charts',
                        'Kasar and discount tracking',
                    ]],
                    ['name' => 'Orders', 'icon' => 'file-text', 'description' => 'Quotations and invoices.', 'features' => [
                        'Quotation / invoice builder',
                        'GST toggle and adjustments',
                        'Per-sqft auto-pricing',
                        'Printable invoice formats',
                    ]],
                    ['name' => 'Masters', 'icon' => 'database', 'description' => 'Reference data.', 'features' => [
                        'Parties and referrers',
                        'Products (per sqft) and price tiers',
                        'Locations and invoice formats',
                        'Company profile and branding',
                    ]],
                ],
            ],
            [
                'slug' => 'lexcase-ai-legal',
                'title' => 'LexCase — AI Legal Case Management',
                'client' => 'LexCase',
                'category' => 'Legal-Tech / AI',
                'year' => 2026,
                'role' => 'Full-Stack Developer',
                'live_url' => 'https://lexcase.fahad-jadiya.com',
                'featured' => true,
                'summary' => 'A multi-tenant legal case management platform with an AI case assistant (IPC/BNS section suggestions), cross-examination prep and an agentic, streaming legal chat.',
                'problem' => 'Law firms needed to manage the full case lifecycle while getting AI help with summaries, applicable sections and hearing preparation.',
                'solution' => 'Laravel 12 + Inertia + Vue (TypeScript) with strict team-scoped multi-tenancy, Groq LLaMA 3.3, JSON-deterministic case analysis, keyword RAG and SSE streaming chat.',
                'outcome' => 'Firms manage cases, hearings, tasks, documents and evidence, with AI that summarizes cases, suggests IPC/BNS sections and drafts cross-exam questions — grounded and cited.',
                'highlights' => [
                    'Strict team-scoped multi-tenancy (cross-tenant access returns 404)',
                    'AI case assistant: JSON-deterministic summary and IPC/BNS section mapping',
                    'Cross-examination prep (opponent and judge) with staleness detection',
                    'Agentic legal chat with tools, SSE streaming and keyword RAG',
                    'Layered architecture (Actions / DTOs / Repositories) with Pest tests',
                ],
                'tech' => ['Laravel', 'Inertia.js', 'Vue.js', 'TypeScript', 'Pinia', 'MySQL', 'Groq / LLaMA 3.3', 'RAG', 'Spatie Permission'],
                'panels' => [
                    ['name' => 'Admin (Owner / Partner)', 'icon' => 'shield', 'description' => 'Firm administration.', 'features' => [
                        'Role and permission management',
                        'Team management',
                        'Settings and activity audit log',
                    ]],
                    ['name' => 'Case Management', 'icon' => 'folder', 'description' => 'The full case lifecycle.', 'features' => [
                        'Cases CRUD with soft-delete',
                        'Tracking timeline (FIR → judgment) with section snapshots',
                        'Clients, hearings and Kanban tasks',
                        'Documents and evidence register',
                    ]],
                    ['name' => 'AI Case Assistant', 'icon' => 'sparkles', 'description' => 'Grounded legal analysis.', 'features' => [
                        'Case summary plus relevant IPC/BNS sections',
                        'Cross-examination prep (opponent / judge)',
                        'Draft analysis from form fields',
                        'SHA-256 staleness detection with regenerate',
                    ]],
                    ['name' => 'AI Legal Chat', 'icon' => 'message-square', 'description' => 'Agentic assistant.', 'features' => [
                        'Agentic tools (search cases / clients / sections)',
                        'Server-sent-events streaming responses',
                        'Keyword RAG grounding with citations',
                        'Multi-turn persisted sessions',
                    ]],
                ],
            ],
        ];
    }
}
