<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{
	public function show(Request $request, Category $category, Topic $topic, User $user, Link $link)
	{
		$topics = $topic::withOrder($request->order)->where('category_id', $category->id)->paginate(20);
		
		// 资源链接
		$links = $link->getAllCached();
		
		// 活跃用户
		$active_users = $user->getActiveUsers();

		return view('topics.index', compact('topics', 'category', 'links', 'active_users'));
		
	}
}

