<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\University;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::with('university')->paginate(20);
        $universities = University::all();
        return view('admin.careers.index', compact('careers', 'universities'));
    }

    public function create()
    {
        $universities = University::all();
        return view('admin.careers.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'university_id' => 'nullable|exists:universities,id',
        ]);

        Career::create([
            'name' => $request->name,
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('admin.careers.index')->with('success', 'Carrera creada.');
    }

    public function edit(Career $career)
    {
        $universities = University::all();
        return view('admin.careers.edit', compact('career', 'universities'));
    }

    public function update(Request $request, Career $career)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'university_id' => 'nullable|exists:universities,id',
        ]);

        $career->update([
            'name' => $request->name,
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('admin.careers.index')->with('success', 'Carrera actualizada.');
    }

    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()->route('admin.careers.index')->with('success', 'Carrera eliminada.');
    }
}
