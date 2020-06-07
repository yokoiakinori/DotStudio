<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreate;
use App\Http\Requests\StoreComment;
use App\Product;
use App\User;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;

class ProductController extends Controller
{
	public function __construct()
	{
		// 認証が必要
		$this->middleware('auth')->except(['index', 'show']);
	}

	public function create(ProductCreate $request)
	{
		$product = new Product();
		$product->user_id = Auth::id();
		$product->productname = $request->productname;
		$product->linedot = $request->linedot;
		$product->alldot = $request->alldot;
		$product->colors = str_repeat("white_", $request->alldot);
		$product->save();
		return $product;
	}

	public function list(Request $request)
	{
		$userid = Auth::id();
		$list = User::with('products')->find($userid)->toArray();
		return response($list, 200);
	}

	public function dotsave(Request $request)
	{
		$userid = Auth::id();
		$products = Product::with('user')->where('user_id', $userid)->orderBy('id', 'asc')->get();
		$currentid = $request->currentProduct;
		$product = $products[$currentid - 1];
		$color = "";
		foreach ($request['dots'] as $item) {
			$color = $color . $item['color'] . '_';
		}
		$product->colors = $color;
		$product->save();
	}

	public function current(Request $request)
	{
		$userid = Auth::id();
		$products = Product::with('user')->where('user_id', $userid)->orderBy('id', 'asc')->get();
		$product = $products[$request['id'] - 1]->colors;
		$productcolors = explode("_", $product);
		return response($productcolors, 200);
	}

	public function index()
	{
		$products = Product::with('user', 'likes')->orderBy(Product::CREATED_AT, 'desc')->paginate();
		return $products;
	}

	public function show(string $id)
	{
		$product = Product::where('id', $id)->with('user', 'comments.user', 'likes')->first();
		return $product ?? abort(404);
	}

	public function delete(String $id)
	{
		Product::destroy($id);
	}

	public function addComment(Product $product, StoreComment $request)
	{
		$comment = new Comment();
		$comment->content = $request->get('content');
		$comment->user_id = Auth::user()->id;
		$product->comments()->save($comment);

		$new_comment = Comment::where('id', $comment->id)->with('user')->first();
		return response($new_comment, 201);
	}

	public function like(string $id)
	{
		$product = Product::where('id', $id)->with('likes')->first();
		if (!$product) {
			abort(404);
		}

		$product->likes()->detach(Auth::user()->id);
		$product->likes()->attach(Auth::user()->id);
		return ["product_id" => $id];
	}

	public function unlike(string $id)
	{
		$product = Product::where('id', $id)->with('likes')->first();

		if (!$product) {
			abort(404);
		}

		$product->likes()->detach(Auth::user()->id);
		return ["product_id" => $id];
	}
}
