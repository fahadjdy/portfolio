<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\SchemaBuilder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request, SchemaBuilder $schema)
    {
        abort_unless((bool) settings('blog_enabled'), 404);

        $posts = BlogPost::published()
            ->with('category:id,name,slug', 'techTags:id,name,slug')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.blog.index', [
            'posts' => $posts,
            'schema' => $schema->graph([
                $schema->person(),
                $schema->website(),
                $schema->breadcrumb([['Home', route('home')], ['Blog', route('blog.index')]]),
            ]),
        ]);
    }

    public function show(BlogPost $post, SchemaBuilder $schema)
    {
        abort_unless((bool) settings('blog_enabled'), 404);
        abort_unless($post->status === 'published' && $post->published_at && $post->published_at->isPast(), 404);

        $post->load('category:id,name,slug', 'techTags:id,name,slug');
        $post->increment('views');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
            ->orderByDesc('published_at')
            ->with('category:id,name')
            ->limit(3)
            ->get();

        return view('public.blog.show', [
            'post' => $post,
            'related' => $related,
            'schema' => $schema->graph([
                $schema->person(),
                $schema->breadcrumb([
                    ['Home', route('home')],
                    ['Blog', route('blog.index')],
                    [$post->title, route('blog.show', $post)],
                ]),
                $schema->article($post),
            ]),
        ]);
    }
}
