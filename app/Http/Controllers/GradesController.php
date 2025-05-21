<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;

class GradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$grades = Grade::paginate();
		return view('grades.index', compact('grades'));
	}

    public function show(Grade $grade)
    {
        return view('grades.show', compact('grade'));
    }

	public function create(Grade $grade)
	{
		return view('grades.create_and_edit', compact('grade'));
	}

	public function store(GradeRequest $request)
	{
		$grade = Grade::create($request->all());
		return redirect()->route('grades.show', $grade->id)->with('message', 'Created successfully.');
	}

	public function edit(Grade $grade)
	{
        $this->authorize('update', $grade);
		return view('grades.create_and_edit', compact('grade'));
	}

	public function update(GradeRequest $request, Grade $grade)
	{
		$this->authorize('update', $grade);
		$grade->update($request->all());

		return redirect()->route('grades.show', $grade->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Grade $grade)
	{
		$this->authorize('destroy', $grade);
		$grade->delete();

		return redirect()->route('grades.index')->with('message', 'Deleted successfully.');
	}
}