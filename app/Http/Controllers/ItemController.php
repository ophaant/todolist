<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Checklist;
use App\Models\Item;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request, Checklist $checklist)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $request->validate(['name' => 'required|string']);
        $item = $checklist->items()->create(['name' => $request->name, 'completed' => false]);
        return $this->success($item->toArray(), 201);
    }

    public function show(Checklist $checklist, Item $item)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $item = $checklist->items()->findOrFail($item->id);
        return $this->success($item->toArray());
    }

    public function update(Request $request, Checklist $checklist, Item $item)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $item = $checklist->items()->findOrFail($item->id);
        $item->update($request->only('name'));
        return $this->success($item->toArray(), 200, config('rc.update_successfully'));
    }

    public function toggleStatus(Checklist $checklist, Item $item)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $item = $checklist->items()->findOrFail($item->id);
        $item->completed = !$item->completed;
        $item->save();
        return $this->success($item->toArray(), 200, config('rc.update_successfully'));
    }

    public function destroy(Checklist $checklist, Item $item)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $item = $checklist->items()->findOrFail($item->id);
        $item->delete();
        return $this->success([], 200, config('rc.delete_successfully'));
    }
}
