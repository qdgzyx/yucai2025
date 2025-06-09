<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Banji;
use Illuminate\Http\Request;
use App\Imports\BanjisImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\BanjiRequest;
use App\Models\User;

class BanjisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'assignmentshow']]);
    }

	public function index()
	{
		$banjis = Banji::with('grade', 'user')->paginate(10);
		return view('banjis.index', compact('banjis'));
	}

    public function show(Banji $banji)
    {
        return view('banjis.show', compact('banji'));
    }
    
    public function assignmentshow(Banji $banji) { 
        $assignments = $banji->assignments()
            ->with(['subject', 'teacher'])
            ->active()
            ->get()
            ->groupBy('subject.name');
            
        return view('banjis.assignmentshow', compact('banji', 'assignments'));
    }
    
    public function create(Banji $banji)
    {
        $grades = \App\Models\Grade::all(); // 获取所有年级数据
        $teachers = User::all(); // 获取所有用户作为教师列表
        return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers')); // 添加teachers参数
    }

	public function store(BanjiRequest $request)
	{
		$banji = Banji::create($request->all());
		return redirect()->route('banjis.show', $banji->id)->with('message', 'Created successfully.');
	}

	public function edit(Banji $banji)
	{
		$this->authorize('update', $banji);
		$grades = \App\Models\Grade::all();
		$teachers = \App\Models\User::all(); // 获取所有用户作为教师列表
		return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
	}
}