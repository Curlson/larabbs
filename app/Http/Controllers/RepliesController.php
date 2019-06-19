<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function store(ReplyStoreRequest $request, Reply $reply)
	{
	    $reply->content = $request->contents;
	    $reply->topic_id = $request->topic_id;
	    $reply->user_id = \Auth::id();
	    
	    $reply->save();
	    
		return redirect()->to($reply->topic->link())->with('success', '评论创建成功!');
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->route('replies.index')->with('success', '删除成功!');
	}
}
