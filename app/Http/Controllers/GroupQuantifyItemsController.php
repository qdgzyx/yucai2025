<?php

namespace App\Http\Controllers;

use App\Models\GroupQuantifyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GroupQuantifyItemRequest;

class GroupQuantifyItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $group_quantify_items = GroupQuantifyItem::paginate();
        return view('group_quantify_items.index', compact('group_quantify_items'));
    }

    public function show(GroupQuantifyItem $group_quantify_item)
    {
        return view('group_quantify_items.show', compact('group_quantify_item'));
    }

    public function create(GroupQuantifyItem $group_quantify_item)
    {
        if (!Auth::check()) {
            abort(403, '请先登录');
        }
        return view('group_quantify_items.create_and_edit', compact('group_quantify_item'));
    }

    public function store(GroupQuantifyItemRequest $request)
    {
        $group_quantify_item = GroupQuantifyItem::create($request->all());
        return redirect()->route('group_quantify_items.show', $group_quantify_item->id)->with('message', 'Created successfully.');
    }

    public function edit(GroupQuantifyItem $group_quantify_item)
    {
        return view('group_quantify_items.create_and_edit', compact('group_quantify_item'));
    }

    public function update(GroupQuantifyItemRequest $request, GroupQuantifyItem $group_quantify_item)
    {
        $group_quantify_item->update($request->all());
        return redirect()->route('group_quantify_items.show', $group_quantify_item->id)->with('message', 'Updated successfully.');
    }

    public function destroy(GroupQuantifyItem $group_quantify_item)
    {
        $group_quantify_item->delete();
        return redirect()->route('group_quantify_items.index')->with('message', 'Deleted successfully.');
    }
}