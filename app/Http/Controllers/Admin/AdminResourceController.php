<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\UploadCoverImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

abstract class AdminResourceController extends Controller
{
    use UploadCoverImage;

    abstract protected function returnsTo(): string;

    abstract protected function modelClass(): string;

    abstract protected function folder(): string;

    abstract protected function viewNamespace(): string;

    abstract protected function storeRequest(): string;

    abstract protected function updateRequest(): string;

    protected function options(): array
    {
        return [];
    }

    public function index(Request $request): Response
    {
        $modelClass = $this->modelClass();
        $view = $this->viewNamespace();
        $folder = $this->folder();

        $items = $modelClass::all();

        $propName = strtolower(class_basename($modelClass)).'s';

        return Inertia::render(
            "{$view}/Index",
            array_merge([
                $propName => $items,
            ], $this->options())
        );
    }

    // wraps our inertia::render to include model-specific options
    protected function inertia(string $component, array $props = []): Response
    {
        return Inertia::render(
            $component,
            array_merge($props, $this->options())
        );
    }

    public function create()
    {
        return $this->inertia("{$this->viewNamespace()}/Create", [
            'initial' => [],
        ]);
    }

    public function edit($id)
    {
        $modelClass = $this->modelClass();
        $view = $this->viewNamespace();

        $m = $modelClass::findOrFail($id);

        $propName = Str::singular($this->folder());

        $data = $m->toArray();

        return $this->inertia(
            "{$view}/Edit",
            [
                $propName => $data,
            ]
        );
    }

    public function store(Request $request)
    {
        $formRequestClass = $this->storeRequest();
        $validatedRequest = app($formRequestClass);

        $data = $validatedRequest->validated();

        $data = $this->mergeCoverImage(
            $validatedRequest,
            $data,
            null,
            $this->folder()
        );

        $modelClass = $this->modelClass();
        $modelClass::create($data);

        return redirect()->route($this->returnsTo());
    }

    public function update(Request $request, $id)
    {
        $formRequestClass = $this->updateRequest();
        $validatedRequest = app($formRequestClass);

        $data = $validatedRequest->validated();

        $modelClass = $this->modelClass();
        $model = $modelClass::findOrFail($id);

        $data = $this->mergeCoverImage(
            $validatedRequest,
            $data,
            $model->cover_image,
            $this->folder()
        );

        $model->update($data);

        $singular = Str::singular(Str::title($this->folder()));

        return redirect()
            ->route($this->returnsTo())
            ->with('success', "{$singular} updated successfully.");
    }

    public function destroy($id)
    {
        $modelClass = $this->modelClass();
        $m = $modelClass::findOrFail($id);
        $m->delete();

        return redirect()->route($this->returnsTo())
            ->with('success', 'Deleted.');
    }
}
