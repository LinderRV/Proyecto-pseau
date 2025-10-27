<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::paginate(20);
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:universities,name',
        ]);

        University::create(['name' => $request->name]);
        return redirect()->route('admin.universities.index')->with('success', 'Universidad creada.');
    }

    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $request->validate(['name' => 'required|string|max:255|unique:universities,name,' . $university->id]);
        $university->update(['name' => $request->name]);
        return redirect()->route('admin.universities.index')->with('success', 'Universidad actualizada.');
    }

    public function destroy(University $university)
    {
        $university->delete();
        return redirect()->route('admin.universities.index')->with('success', 'Universidad eliminada.');
    }
}
