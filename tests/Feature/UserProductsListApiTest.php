<?php

namespace Tests\Feature;

use App\User;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class UserProductsListApiTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function UserProductsList()
	{
		factory(Product::class, 5)->create();
		$userid = mt_rand(1, 5);
		$response = $this->json('GET', route('user.products', [
			'id' => $userid,
		]));

		$response->assertStatus(200)
			->assertJson(['data' => [0 => ['user_id' => $userid]]]);
	}
}
