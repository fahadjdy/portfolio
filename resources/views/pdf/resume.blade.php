<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 34px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; line-height: 1.5; }
        h1 { font-size: 24px; margin: 0; color: #0f172a; }
        .role { color: #4f46e5; font-size: 12px; font-weight: bold; margin: 2px 0 0; }
        .contact { font-size: 9.5px; color: #475569; margin-top: 6px; }
        .contact a { color: #475569; text-decoration: none; }
        .rule { border-bottom: 2px solid #4f46e5; margin: 10px 0 14px; }
        h2 { font-size: 12px; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin: 16px 0 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px; }
        .item { margin-bottom: 10px; }
        .item-head { margin-bottom: 2px; }
        .title { font-weight: bold; color: #0f172a; font-size: 11.5px; }
        .meta { color: #64748b; font-size: 9.5px; }
        .desc { margin: 3px 0 0; color: #334155; }
        ul { margin: 4px 0 0; padding-left: 16px; }
        li { margin: 1px 0; color: #334155; }
        .skillrow { margin-bottom: 4px; }
        .skillcat { font-weight: bold; color: #0f172a; }
        .proj { margin-bottom: 8px; }
        .tech { color: #64748b; font-size: 9px; font-style: italic; }
        p { margin: 0 0 6px; }
    </style>
</head>
<body>
    <h1>{{ $name }}</h1>
    <p class="role">{{ $tagline }}</p>
    <p class="contact">
        @if($email){{ $email }}@endif
        @if($phone) &nbsp;•&nbsp; {{ $phone }}@endif
        @if($location) &nbsp;•&nbsp; {{ $location }}@endif
        @if($website) &nbsp;•&nbsp; {{ preg_replace('#^https?://#', '', $website) }}@endif
        @foreach($socials as $s)
            @if(!in_array($s->platform, ['email','website'])) &nbsp;•&nbsp; {{ $s->label ?: $s->platform }}: {{ preg_replace('#^https?://#', '', $s->url) }}@endif
        @endforeach
    </p>
    <div class="rule"></div>

    @if($summary)
        <h2>Summary</h2>
        @foreach(preg_split('/\n\n+/', trim(strip_tags($summary))) as $para)
            <p>{{ $para }}</p>
        @endforeach
    @endif

    @if($experiences->count())
        <h2>Experience</h2>
        @foreach($experiences as $exp)
            <div class="item">
                <div class="item-head">
                    <span class="title">{{ $exp->title }}</span>
                    <span class="meta"> — {{ $exp->company }}@if($exp->location), {{ $exp->location }}@endif</span>
                    <span class="meta"> ({{ $exp->start_date?->format('M Y') }} – {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }})</span>
                </div>
                @if($exp->description)<p class="desc">{{ $exp->description }}</p>@endif
                @if(!empty($exp->highlights))
                    <ul>@foreach($exp->highlights as $h)<li>{{ $h }}</li>@endforeach</ul>
                @endif
            </div>
        @endforeach
    @endif

    @if($skillCategories->count())
        <h2>Skills</h2>
        @foreach($skillCategories as $cat)
            @if($cat->skills->count())
                <p class="skillrow"><span class="skillcat">{{ $cat->name }}:</span> {{ $cat->skills->pluck('name')->implode(', ') }}</p>
            @endif
        @endforeach
    @endif

    @if($projects->count())
        <h2>Selected Projects</h2>
        @foreach($projects as $p)
            <div class="proj">
                <span class="title">{{ $p->title }}</span>@if($p->category)<span class="meta"> — {{ $p->category }}</span>@endif
                <p class="desc">{{ $p->summary }}</p>
                @if($p->techTags->count())<p class="tech">{{ $p->techTags->pluck('name')->implode(' · ') }}</p>@endif
            </div>
        @endforeach
    @endif

    @if($education->count())
        <h2>Education</h2>
        @foreach($education as $edu)
            <div class="item">
                <span class="title">{{ $edu->degree }}@if($edu->field_of_study), {{ $edu->field_of_study }}@endif</span>
                <span class="meta"> — {{ $edu->institution }} ({{ $edu->start_year }}–{{ $edu->end_year ?: 'Present' }})</span>
            </div>
        @endforeach
    @endif
</body>
</html>
