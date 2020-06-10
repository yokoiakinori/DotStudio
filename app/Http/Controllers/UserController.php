<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\User;
use App\Userthumbnail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;

class UserController extends Controller
{
	public function __construct()
	{
		// 認証が必要
		$this->middleware('auth')->only(['myacount']);
	}

	public function myacount()
	{
		$thumbnail = Auth::user()->userthumbnail;
		$user = Auth::user();
		$user->userthumbnail = $thumbnail;
		return $user;
	}

	public function list()
	{
		$users = User::with(['products' => function ($query) {
			$query->limit(3);
		}, 'userthumbnail'])->orderBy(User::CREATED_AT, 'desc')->paginate();
		return $users;
	}

	public function products(String $id)
	{
		$products = Product::where('user_id', $id)->orderBy('created_at', 'desc')->paginate();
		return $products;
	}

	public function currentuser(String $id)
	{
		$user = User::with('userthumbnail')->where('id', $id)->get();
		return $user;
	}
}
