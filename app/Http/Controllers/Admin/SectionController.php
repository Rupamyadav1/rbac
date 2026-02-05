<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\Middleware;
class SectionController extends Controller
{

  public static function middleware(): array
    {
        return [
            new Middleware('permission:create cms', only: ['create']),
            new Middleware('permission:view cms', only: ['index']),
            new Middleware('permission:edit cms', only: ['edit']),
            new Middleware('permission:delete cms', only: ['destroy']),
        ];
    }
    public function index()
    {
        $sections = Section::orderBy('position')->paginate(10);
        return view('admin.section.index', compact('sections'));
    }

    public function create(){
        return view('admin.section.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'nullable|string|max:255|unique:sections,slug',
            'position' => 'nullable|integer',
            'redirect_to'=>'required',
            'status'   => 'required|boolean',
        ]);

        Section::create([
            'title'     => $request->title,
            'slug'     => $request->slug 
                            ? Str::slug($request->slug)
                            : Str::slug($request->title),
            'position' => $request->position ?? (Section::max('position') + 1),
            'status'   => $request->status,
            'redirect_to'=>$request->redirect_to,
        ]);

        return redirect()
            ->route('section.index')
            ->with('success', 'Section created successfully');
    }

    public function edit($id){
         $section = Section::findOrFail($id);
         return view('admin.section.edit',compact('section'));

    }

   
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'required|string|max:255|unique:sections,slug,' . $section->id,
            'position' => 'nullable|integer',
            'redirect_to'=>'required',
            'status'   => 'required|boolean',
        ]);

        $section->update([
            'title'     => $request->title,
            'slug'     => Str::slug($request->slug),
            'position' => $request->position ?? $section->position,
            'redirect_to'=>$request->redirect_to,
            'status'   => $request->status,
        ]);

        return redirect()
            ->route('section.index')
            ->with('success', 'Section updated successfully');
    }

    public function toggle($id)
    {
        $section = Section::findOrFail($id);
        $section->update(['status' => !$section->status]);

        return back();
    }
}
