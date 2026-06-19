<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Config-driven CRUD base for the simple admin resources. Subclasses declare
 * the model, labels, table columns, form fields and validation rules; the two
 * generic Inertia pages (admin/crud/Index, admin/crud/Form) render everything.
 */
abstract class CrudController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    protected string $routeBase;   // e.g. admin.services

    protected string $singular;    // e.g. Service

    protected string $plural;      // e.g. Services

    protected bool $sortable = true;

    protected string $uploadDir = 'images';

    public function __construct(protected ImageService $images)
    {
    }

    abstract protected function columns(): array;

    /** @return array<int, array<string, mixed>> field configs */
    abstract protected function fields(?Model $item): array;

    abstract protected function rules(Request $request, ?Model $item): array;

    protected function indexQuery()
    {
        $q = $this->model::query();

        return $this->sortable ? $q->orderBy('position')->orderBy('id') : $q->latest();
    }

    protected function transform(Model $item): array
    {
        return $item->toArray();
    }

    protected function meta(): array
    {
        return [
            'singular' => $this->singular,
            'plural' => $this->plural,
            'routeBase' => $this->routeBase,
            'sortable' => $this->sortable,
        ];
    }

    public function index()
    {
        return Inertia::render('admin/crud/Index', [
            'resource' => $this->meta(),
            'columns' => $this->columns(),
            'items' => $this->indexQuery()->get()->map(fn (Model $m) => $this->transform($m))->values(),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/crud/Form', [
            'resource' => $this->meta(),
            'item' => null,
            'fields' => $this->fields(null),
        ]);
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);

        return Inertia::render('admin/crud/Form', [
            'resource' => $this->meta(),
            'item' => $this->transform($item),
            'fields' => $this->fields($item),
        ]);
    }

    public function store(Request $request)
    {
        $item = new $this->model;
        $this->persist($item, $request, $request->validate($this->rules($request, null)));

        return redirect()->route($this->routeBase.'.index')->with('success', "{$this->singular} created.");
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $this->persist($item, $request, $request->validate($this->rules($request, $item)));

        return redirect()->route($this->routeBase.'.index')->with('success', "{$this->singular} updated.");
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $this->beforeDelete($item);
        $item->delete();

        return back()->with('success', "{$this->singular} deleted.");
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('ids', []) as $i => $id) {
            $this->model::where('id', $id)->update(['position' => $i + 1]);
        }

        return back();
    }

    protected function persist(Model $item, Request $request, array $data): void
    {
        $existing = $item->exists ? $item : null;

        foreach ($this->fields($existing) as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';

            if ($type === 'image') {
                if ($request->hasFile($name)) {
                    if ($existing && $item->{$name} && ! str_starts_with((string) $item->{$name}, 'http')) {
                        $this->images->delete($item->{$name});
                    }
                    $item->{$name} = $this->images->storeOptimized($request->file($name), $this->uploadDir, 1600);
                }

                continue;
            }

            if (! array_key_exists($name, $data)) {
                if ($type === 'toggle') {
                    $item->{$name} = false; // unchecked toggles are absent
                }

                continue;
            }

            $value = $data[$name];
            $item->{$name} = ($value === '') ? null : $value;
        }

        $item->save();
    }

    protected function beforeDelete(Model $item): void
    {
    }
}
