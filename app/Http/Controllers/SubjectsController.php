<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;

class SubjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$subjects = Subject::paginate();
		return view('subjects.index', compact('subjects'));
	}

    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }

	public function create(Subject $subject)
	{
		return view('subjects.create_and_edit', compact('subject'));
	}

	public function store(SubjectRequest $request)
	{
		$subject = Subject::create($request->all());
		return redirect()->route('subjects.show', $subject->id)->with('message', 'Created successfully.');
	}

	public function edit(Subject $subject)
	{
        $this->authorize('update', $subject);
		return view('subjects.create_and_edit', compact('subject'));
	}

	public function update(SubjectRequest $request, Subject $subject)
	{
		$this->authorize('update', $subject);
		$subject->update($request->all());

		return redirect()->route('subjects.show', $subject->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Subject $subject)
	{
		$this->authorize('destroy', $subject);
		$subject->delete();

		return redirect()->route('subjects.index')->with('message', 'Deleted successfully.');
	}
}