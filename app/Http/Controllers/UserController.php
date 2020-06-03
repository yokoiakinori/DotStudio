<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;

class UserController extends Controller
{
	public function list()
	{
		$users = User::orderBy(User::CREATED_AT, 'desc')->paginate();
		return $users;
	}

	public function products(String $id)
	{
		$products = Product::where('user_id', $id)->orderBy('created_at', 'desc')->paginate();
		return $products;
	}

	public function currentuser(String $id)
	{
		$user = User::where('id', $id)->get();
		return $user;
	}
}
