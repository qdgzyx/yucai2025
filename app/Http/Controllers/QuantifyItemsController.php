<?php

namespace App\Http\Controllers;

use App\Models\QuantifyItem;
use App\Models\Banji;
use App\Models\User;
use App\Models\Semester;
use App\Models\QuantifyType;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuantifyItemRequest;

class QuantifyItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$quantify_items = QuantifyItem::paginate();
		return view('quantify_items.index', compact('quantify_items'));
	}

    public function show(QuantifyItem $quantify_item)
    {
        return view('quantify_items.show', compact('quantify_item'));
    }

	public function create(QuantifyItem $quantify_item)
	{
	    // 检查用户是否认证
	    if (!Auth::check()) {
	        abort(403, '请先登录');
	    }
	
	    try {
	        $quantify_item->user_id = Auth::id();
	        $quantify_item->banji_id = Auth::user()->banji_id;
	        
	        // 只加载必要字段
	       
	        $users = User::pluck('name', 'id');
			$semesters = Semester::all();
			$quantifyTypes = QuantifyType::whereNull('parent_id')->get();
			return view('quantify_items.create_and_edit', compact('quantify_item', 'quantifyTypes', 'users', 'semesters'));
	        
	    } catch (\Exception $e) {
	        // 记录错误日志
	        \Log::error('创建量化项失败: '.$e->getMessage());
	        abort(500, '创建量化项时发生错误');
	    }
	}

	public function store(QuantifyItemRequest $request)
	{
		$this->authorize('create', new QuantifyItem);
		
		$quantify_item = QuantifyItem::create($request->all());
		return redirect()->route('quantify_items.show', $quantify_item->id)->with('message', 'Created successfully.');
	}

	public function edit(QuantifyItem $quantify_item)
	{
        $this->authorize('update', $quantify_item);
		return view('quantify_items.create_and_edit', compact('quantify_item'));
	}

	public function update(QuantifyItemRequest $request, QuantifyItem $quantify_item)
	{
		$this->authorize('update', $quantify_item);
		$quantify_item->update($request->all());

		return redirect()->route('quantify_items.show', $quantify_item->id)->with('message', 'Updated successfully.');
	}

	public function destroy(QuantifyItem $quantify_item)
	{
		$this->authorize('destroy', $quantify_item);
		$quantify_item->delete();

		return redirect()->route('quantify_items.index')->with('message', 'Deleted successfully.');
	}
}