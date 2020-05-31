<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
	public function list()
	{
		$users = User::orderBy(User::CREATED_AT, 'desc')->paginate();
		return $users;
	}
}
