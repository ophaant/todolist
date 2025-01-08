<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistRequest;
use App\Http\Resources\ChecklistResource;
use App\Models\Checklist;
use App\Traits\ApiResponseTrait;

class ChecklistController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $checklists = auth()->user()->checklists()->with('items')->get();
        return $this->success($checklists->toArray(), 200);
    }

    public function store(ChecklistRequest $request)
    {
        $request->validate(['name' => 'required|string']);
        $checklist = auth()->user()->checklists()->create(['name' => $request->name]);
        return $this->success($checklist->toArray(), 201,config('rc.create_successfully'));
    }

    public function show(Checklist $checklist)
    {
        $checklist = auth()->user()->checklists()->with('items')->findOrFail($checklist->id);
        return $this->success($checklist->toArray(), 200);
    }

    public function destroy(Checklist $checklist)
    {
        $checklist = auth()->user()->checklists()->findOrFail($checklist->id);
        $checklist->delete();
        return $this->success([], 200,config('rc.delete_successfully'));
    }
}
