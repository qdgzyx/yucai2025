<?php

namespace App\Http\Controllers;

use App\Models\Banji;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BanjiRequest;

class BanjisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$banjis = Banji::paginate();
		return view('banjis.index', compact('banjis'));
	}

    public function show(Banji $banji)
    {
        return view('banjis.show', compact('banji'));
    }

	public function create(Banji $banji)
	{
		return view('banjis.create_and_edit', compact('banji'));
	}

	public function store(BanjiRequest $request)
	{
		$banji = Banji::create($request->all());
		return redirect()->route('banjis.show', $banji->id)->with('message', 'Created successfully.');
	}

	public function edit(Banji $banji)
	{
        $this->authorize('update', $banji);
		return view('banjis.create_and_edit', compact('banji'));
	}

	public function update(BanjiRequest $request, Banji $banji)
	{
		$this->authorize('update', $banji);
		$banji->update($request->all());

		return redirect()->route('banjis.show', $banji->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Banji $banji)
	{
		$this->authorize('destroy', $banji);
		$banji->delete();

		return redirect()->route('banjis.index')->with('message', 'Deleted successfully.');
	}
}