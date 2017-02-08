<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Post;
use App\User;
use App\Like;
Class PostController extends Controller
{
	public function getDashboard(){
		$posts = Post::orderBy('id', 'desc')->get();
		return view('dashboard',['posts' => $posts ]);
	}
	public function postCreatePost(Request $request)
	{

        //validation
		$this->validate($request,[
			'body' => 'required|max:1000',
			]);
		$post = new Post();
		$post->body = $request['body'];
		$message = 'There was an error';
		
		if($request->user()->posts()->save($post))
		{
			$message = 'Post successfully created!';
		}

		return redirect()->route('dashboard')->with(['message' => $message]);

	}
	public function getDeletePost($post_id)
	{
		$post = Post::where('id', $post_id)->first();
		if(Auth::user() != $post->user)
		{
			return redirect()->back();
		}
		$post->delete();
		return redirect()->route('dashboard')->with(['message' => 'Successfully deleted!']);
	}
	public function postEditPost(Request $request)
	{ 
		//validation
		$this->validate($request,[
			'post-body' => 'required|max:1000'
			]);
		$post = Post::find($request['post_id']);
		if(Auth::user() != $post->user)
		{
			return redirect()->back();
		}
		$post->body = $request['post-body'];
		$post->update(); 
		return response()->json(['message' => 'Post Edited!','post-body' => $post->body],200);
	}
	public function postLikePost(Request $request)
	{
		$post_id = $request['post_id'];
		$is_like = $request['isLike'] === 'true';
		$update = false;
		$post = Post::find($post_id);
		if(!$post)
		{
			return null;
		}
		$user = Auth::user();
		$like = $user->likes()->where('post_id',$post_id)->first();
		if($like)
		{
			$already_like = $like->like;
			$update = true;
			if($already_like == $is_like)
			{
				$like->delete();
				return null;
			}
		}
		else
		{
			$like = new Like();
		}
		$like->like = $is_like;
		$like->user_id = $user->id;
		$like->post_id = $post_id;
		if($update)
		{
			$like->update();
		}
		else
		{
			$like->save();
		}
		return null;

	}

}