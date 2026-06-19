<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\TechTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Engineering' => 'Architecture, patterns and practical Laravel + Vue engineering.',
            'AI & LLMs' => 'Building with LLMs — RAG, tool-calling and AI product design.',
            'Case Studies' => 'Deep dives into real production projects.',
        ];

        $catIds = [];
        $pos = 0;
        foreach ($categories as $name => $desc) {
            $cat = BlogCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $desc, 'position' => ++$pos, 'is_active' => true],
            );
            $catIds[$name] = $cat->id;
        }

        foreach ($this->posts() as $i => $data) {
            $post = BlogPost::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'blog_category_id' => $catIds[$data['category']] ?? null,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'body' => $data['body'],
                    'reading_minutes' => max(1, (int) ceil(str_word_count(strip_tags($data['body'])) / 200)),
                    'status' => 'published',
                    'published_at' => now()->subDays($data['days_ago']),
                    'seo_title' => $data['title'].' | Fahad Jadiya',
                    'seo_description' => Str::limit($data['excerpt'], 155),
                ],
            );

            $tagIds = TechTag::whereIn('name', $data['tags'])->pluck('id')->all();
            $post->techTags()->sync($tagIds);
        }
    }

    private function posts(): array
    {
        return [
            [
                'slug' => 'building-ai-legal-assistant-groq-rag',
                'title' => 'Building an AI legal assistant with Groq + RAG (LexCase)',
                'category' => 'AI & LLMs',
                'days_ago' => 3,
                'tags' => ['Laravel', 'Vue.js', 'Groq / LLaMA 3.3', 'RAG'],
                'excerpt' => 'How I built LexCase — an AI case-management platform that summarizes cases, suggests IPC/BNS sections and preps cross-examination, grounded on the firm\'s own data.',
                'body' => <<<'HTML'
<p>LexCase is a multi-tenant legal case-management platform with an AI layer that lawyers actually trust. The hard part of legal AI isn't generating text — it's making sure the output is grounded, deterministic where it matters, and cited.</p>
<h2>Two AI modes, on purpose</h2>
<p>I split the AI into two distinct modes. The <strong>Case Assistant</strong> uses Groq's <code>llama-3.3-70b-versatile</code> with a strict <code>response_format: json_object</code> so the case summary and the suggested IPC/BNS sections come back as parseable, predictable JSON — never prose I have to scrape. The <strong>Legal Chat</strong> is agentic and streamed: it can call tools (search cases, look up statutes, create tasks) and replies token-by-token over Server-Sent Events.</p>
<h2>Grounding with keyword RAG</h2>
<p>Instead of an embeddings pipeline, I used a pragmatic keyword retriever over the firm's statute library and its own past cases. It's fast, dependency-free, and keeps answers anchored to real records — which for legal work matters far more than semantic cleverness.</p>
<h2>Staleness detection</h2>
<p>AI results are cached with a SHA-256 signature of the case facts plus its tracking timeline. The moment the case changes, the cached analysis is flagged stale and the lawyer can regenerate. Cheap, correct, and transparent.</p>
<h2>Takeaways</h2>
<ul>
<li>Use deterministic JSON output for anything you parse.</li>
<li>Ground every AI claim in your own data and surface citations.</li>
<li>Cache AI results with a content signature so staleness is explicit.</li>
</ul>
HTML,
            ],
            [
                'slug' => 'practical-rag-for-saas-2026',
                'title' => 'Practical RAG for SaaS apps in 2026',
                'category' => 'AI & LLMs',
                'days_ago' => 7,
                'tags' => ['RAG', 'OpenAI-compatible API', 'Groq / LLaMA 3.3'],
                'excerpt' => 'You don\'t always need a vector database. A practical guide to retrieval-augmented generation for real SaaS products — and when keyword RAG beats embeddings.',
                'body' => <<<'HTML'
<p>Retrieval-augmented generation (RAG) is the difference between an LLM that hallucinates and one that answers from your data. But the 2023-era "embed everything into a vector DB" advice is overkill for a lot of SaaS use cases.</p>
<h2>Start with keyword retrieval</h2>
<p>If your corpus is structured (statutes, products, tickets, docs) and modest in size, a well-built keyword/full-text retriever is fast, debuggable and free of an extra moving part. Ship that first; reach for embeddings when recall actually suffers.</p>
<h2>Inject, don't dump</h2>
<p>Retrieve the few most relevant chunks and inject them into the system prompt with clear delimiters and source ids. Ask the model to cite those ids. Dumping your whole knowledge base wastes tokens and dilutes the answer.</p>
<h2>Make grounding visible</h2>
<p>Surface the retrieved sources in the UI. Users trust AI far more when they can click through to the evidence — and it makes debugging hallucinations trivial.</p>
<h2>Operational notes</h2>
<ul>
<li>Rate-limit AI endpoints (20–30/min) to control cost.</li>
<li>Cache results keyed by a hash of the inputs.</li>
<li>Keep a provider-agnostic client so you can swap Groq / OpenAI-compatible backends.</li>
</ul>
HTML,
            ],
            [
                'slug' => 'agentic-tool-calling-patterns',
                'title' => 'Agentic tool-calling with LLMs: patterns that actually work',
                'category' => 'AI & LLMs',
                'days_ago' => 12,
                'tags' => ['OpenAI-compatible API', 'Groq / LLaMA 3.3', 'Laravel'],
                'excerpt' => 'Read tools, write tools, confirmation gates and the resolve-then-stream loop — lessons from shipping agentic assistants in healthcare and legal apps.',
                'body' => <<<'HTML'
<p>Across WeCare (clinical) and LexCase (legal) I shipped agentic assistants that can read <em>and</em> write real records. Here are the patterns that kept them safe and useful.</p>
<h2>Separate read and write tools</h2>
<p>Read tools (search patients, fetch a case, list hearings) can run freely. Write tools (register a patient, add a treatment, create a task) sit behind an explicit confirmation gate — the model proposes, the human approves. This single rule prevents almost every "the AI did something I didn't want" incident.</p>
<h2>Resolve tools first, then stream</h2>
<p>Smaller open models are more reliable when you run the tool-calling phase non-streamed, execute the tools, then stream only the final answer. Trying to stream and tool-call simultaneously is where flakiness lives.</p>
<h2>Persist the conversation</h2>
<p>Store messages per user so context survives reloads, and let users edit/regenerate. Tool results should be append-only for an auditable trail.</p>
<h2>Guardrails</h2>
<ul>
<li>Validate every tool's arguments server-side before executing.</li>
<li>Scope tools to the current tenant/user — never trust the model with authorization.</li>
<li>Throttle and log every AI call.</li>
</ul>
HTML,
            ],
            [
                'slug' => 'multi-tenant-saas-laravel-edusphere',
                'title' => 'How I architected a multi-tenant school SaaS (EduSphere)',
                'category' => 'Case Studies',
                'days_ago' => 18,
                'tags' => ['Laravel', 'Vue.js', 'Spatie Permission', 'Docker'],
                'excerpt' => 'Single-database multi-tenancy, school-scoped RBAC, audit logging and a module generator — the architecture behind a 10-role school management platform.',
                'body' => <<<'HTML'
<p>EduSphere manages a school's entire operation — admissions, academics, finance, HR, library and transport — for many schools at once, with strict data isolation.</p>
<h2>Single-database multi-tenancy</h2>
<p>Every tenant-owned model uses a <code>BelongsToSchool</code> trait that adds a global scope and auto-stamps <code>school_id</code> on create. A tenant resolver middleware sets the active school per request, so queries are isolated without scattering <code>where('school_id', ...)</code> everywhere. Cross-tenant access simply resolves to 404.</p>
<h2>School-scoped RBAC</h2>
<p>Roles are per-school (Spatie teams), permissions are global — 140+ fine-grained abilities across modules. The UI mirrors the backend via a permissions composable so widgets never render data a user can't see.</p>
<h2>A module generator</h2>
<p>Each feature is a self-contained module (model → migration → repository → service → request → resource → policy → controller → routes → tests). A <code>make:module</code> command scaffolds the whole thing in seconds, which keeps a large codebase consistent.</p>
<h2>Why it matters</h2>
<p>The payoff is a platform that's secure by default and cheap to extend — new schools onboard without code, and new modules ship without reinventing structure.</p>
HTML,
            ],
            [
                'slug' => 'dynamic-pricing-engine-b2b',
                'title' => 'Designing a 3-tier dynamic pricing engine for B2B',
                'category' => 'Engineering',
                'days_ago' => 24,
                'tags' => ['Laravel', 'Vue.js', 'MySQL'],
                'excerpt' => 'Per-dealer pricing without N+1 chaos: a priority chain of fixed price → category discount → global discount, resolved in batches.',
                'body' => <<<'HTML'
<p>In the B2B Dealer platform every dealer can see a different price for the same product. The trick is making that fast and predictable.</p>
<h2>A priority chain</h2>
<p>The engine resolves price by walking a chain: a dealer-specific <strong>fixed price</strong> wins; otherwise a <strong>category discount %</strong>; otherwise a <strong>global discount %</strong>; otherwise the base price. One clear order of precedence means support can always explain a number.</p>
<h2>Batch resolution</h2>
<p>Resolving prices one product at a time is an N+1 trap. Instead the service takes a set of products and a dealer, loads the relevant overrides once, and computes every price in memory. Catalog pages stay snappy at any size.</p>
<h2>Freeze at order time</h2>
<p>When an order is placed, unit prices are captured onto the order lines. Later pricing changes never rewrite history — invoices and reports stay correct forever.</p>
<h2>Lesson</h2>
<p>Pricing is business logic, not a column. Model it as an explicit, testable service and you can evolve the rules without fear.</p>
HTML,
            ],
            [
                'slug' => 'lead-pipeline-crm-autoconsult',
                'title' => 'An 11-stage lead pipeline that sales teams actually use',
                'category' => 'Case Studies',
                'days_ago' => 30,
                'tags' => ['Laravel', 'Vue.js', 'TypeScript', 'Spatie Permission'],
                'excerpt' => 'Lead scoring, a Kanban pipeline and activity logging in the AutoConsult dealer CRM — and the data model that keeps it honest.',
                'body' => <<<'HTML'
<p>AutoConsult pairs a public vehicle marketplace with a real dealer CRM. The CRM's job is simple to state and hard to do well: never let a lead slip.</p>
<h2>A pipeline as a state machine</h2>
<p>Leads move through 11 explicit stages from New to Deal Closed (or Lost), rendered as a drag-and-drop Kanban. Modeling it as a state machine — not a free-text status — means reporting, automation and funnels are trivial later.</p>
<h2>Lead scoring</h2>
<p>Each lead gets a 0–100 score from signals like buyer verification, phone verification, whether an offer was made, the inquiry type, and engagement. Reps work the hottest leads first without guessing.</p>
<h2>Append-only activity log</h2>
<p>Calls, notes, follow-ups and status changes are append-only. Combined with role scoping (owners see all; executives see only assigned leads) it produces an auditable, trustworthy history.</p>
<h2>Results</h2>
<p>Sales reports — conversion rate, response time, top performers, source breakdown — fall out of the model almost for free, because the structure was right from day one.</p>
HTML,
            ],
            [
                'slug' => 'fast-laravel-blade-sites-seo',
                'title' => 'Fast, SEO-first sites with Laravel Blade in 2026',
                'category' => 'Engineering',
                'days_ago' => 36,
                'tags' => ['Laravel', 'Tailwind CSS', 'Alpine.js'],
                'excerpt' => 'Why I render public marketing pages with Blade + Alpine instead of an SPA — and how to get a near-perfect Lighthouse score on shared hosting.',
                'body' => <<<'HTML'
<p>For content-driven public pages, a server-rendered Blade site beats an SPA on the metrics that matter: time to first byte, largest contentful paint, and crawlability.</p>
<h2>Ship almost no JavaScript</h2>
<p>The public bundle is just Tailwind plus a sliver of Alpine for the mobile menu and accordions. No Vue/Inertia runtime on marketing pages means the browser paints content immediately.</p>
<h2>Images done right</h2>
<p>Every upload is converted to WebP with a responsive srcset via GD, served through a <code>&lt;picture&gt;</code> with explicit width/height so there's zero layout shift. Hero images load eager with high fetch priority; everything else is lazy.</p>
<h2>SEO, AEO and GEO</h2>
<p>Data-driven meta tags, a JSON-LD entity graph, a dynamic sitemap, and an <code>llms.txt</code> for answer engines. Answer-first copy and FAQ schema help both Google snippets and generative engines quote the site accurately.</p>
<h2>It runs anywhere</h2>
<p>No Node process at runtime, database-backed cache/queue, and a token-protected deploy flow — it deploys to plain shared hosting and stays fast.</p>
HTML,
            ],
        ];
    }
}
