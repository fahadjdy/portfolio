<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') — Fahad Jadiya</title>
    {{-- Inline styles only: error pages must render even if the asset build/manifest is missing. --}}
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#f8fafc;color:#0f172a;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem}
        .card{max-width:30rem;text-align:center}
        .code{font-size:5rem;font-weight:800;line-height:1;color:#4f46e5;letter-spacing:-.05em}
        .title{margin-top:.5rem;font-size:1.5rem;font-weight:700}
        .msg{margin-top:.75rem;color:#475569}
        .home{display:inline-block;margin-top:1.75rem;background:#4f46e5;color:#fff;text-decoration:none;font-weight:600;font-size:.95rem;padding:.7rem 1.4rem;border-radius:.6rem}
        .home:hover{background:#4338ca}
    </style>
</head>
<body>
    <div class="card">
        <div class="code">@yield('code')</div>
        <h1 class="title">@yield('title')</h1>
        <p class="msg">@yield('message')</p>
        <a class="home" href="/">Back to home</a>
    </div>
</body>
</html>
