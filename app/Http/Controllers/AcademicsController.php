<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicRequest;

class AcademicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$academics = Academic::paginate();
		return view('academics.index', compact('academics'));
	}

    public function show(Academic $academic)
    {
        return view('academics.show', compact('academic'));
    }

	public function create(Academic $academic)
	{
		return view('academics.create_and_edit', compact('academic'));
	}

	public function store(AcademicRequest $request)
	{
		$academic = Academic::create($request->all());
		return redirect()->route('academics.show', $academic->id)->with('message', 'Created successfully.');
	}

	public function edit(Academic $academic)
	{
        $this->authorize('update', $academic);
		return view('academics.create_and_edit', compact('academic'));
	}

	public function update(AcademicRequest $request, Academic $academic)
	{
		$this->authorize('update', $academic);
		$academic->update($request->all());

		return redirect()->route('academics.show', $academic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Academic $academic)
	{
		$this->authorize('destroy', $academic);
		$academic->delete();

		return redirect()->route('academics.index')->with('message', 'Deleted successfully.');
	}
}