<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$reports = Report::paginate();
		return view('reports.index', compact('reports'));
	}

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

	public function create(Report $report)
	{
		return view('reports.create_and_edit', compact('report'));
	}

	public function store(ReportRequest $request)
	{
		$report = Report::create($request->all());
		return redirect()->route('reports.show', $report->id)->with('message', 'Created successfully.');
	}

	public function edit(Report $report)
	{
        $this->authorize('update', $report);
		return view('reports.create_and_edit', compact('report'));
	}

	public function update(ReportRequest $request, Report $report)
	{
		$this->authorize('update', $report);
		$report->update($request->all());

		return redirect()->route('reports.show', $report->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Report $report)
	{
		$this->authorize('destroy', $report);
		$report->delete();

		return redirect()->route('reports.index')->with('message', 'Deleted successfully.');
	}
}