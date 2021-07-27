<?php

namespace VCComponent\Laravel\Redirecter\Test\Feature;

use VCComponent\Laravel\Redirecter\Test\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Redirecter\Entities\RedirectUrls;

class RedirectControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_get_paginated_redirect_by_admin()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($redirect) {
            unset($redirect['created_at']);
            unset($redirect['updated_at']);
            return $redirect;
        })->toArray();

        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $redirects
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
    }

    /** @test */
    public function can_get_paginated_redirect_with_constraints()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $from_url_constraints = $redirects[0]->from_url;
        $to_url_constraints = $redirects[0]->to_url;

        $redirects = $redirects->map(function ($redirect) {
            unset($redirect['created_at']);
            unset($redirect['updated_at']);
            return $redirect;
        })->toArray();

        $constraints = '{"from_url":"' . $from_url_constraints . '", "to_url":"' . $to_url_constraints . '"}';

        $response = $this->call('GET', 'api/admin/redirect?constraints=' . $constraints);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$redirects[0]]
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
    }

    /** @test */
    public function can_get_paginated_redirect_with_search()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($s) {
            unset($s['updated_at']);
            unset($s['created_at']);
            return $s;
        })->toArray();

        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect?search=' . $redirects[0]['from_url']);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$redirects[0]]
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
    }

    /** @test */
    public function can_get_paginated_redirects_with_order_by()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($s) {
            unset($s['updated_at']);
            unset($s['created_at']);
            return $s;
        })->toArray();

        $order_by = '{"from_url":"desc"}';


        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_ASC, $redirects);

        $listName = array_column($redirects, 'from_url');
        array_multisort($listName, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect?order_by=' . $order_by);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
        $response->assertJson([
            'data' => $redirects,
        ]);
    }

    /** @test */
    public function can_create_redirect_by_admin()
    {
        $redirect = factory(RedirectUrls::class)->make()->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->post('api/admin/redirect', $redirect);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $redirect
        ]);

        $this->assertDatabaseHas('redirect_urls', $redirect);
    }

    /** @test */
    public function should_not_create_redirect_with_empty_url()
    {
        $redirect = factory(RedirectUrls::class)->make([
            'from_url' => null,
        ])->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->post('api/admin/redirect', $redirect);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.'
        ]);
    }

    /** @test */
    public function should_not_create_redirect_with_existed_from_url()
    {
        $redirect = factory(RedirectUrls::class)->create([
            'from_url' => 'exited_url',
        ])->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->post('api/admin/redirect', $redirect);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.'
        ]);
    }

    /** @test */
    public function can_get_redirect_by_admin()
    {
        $redirect = factory(RedirectUrls::class)->create()->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->get('api/admin/redirect/' . $redirect['id']);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $redirect
        ]);
    }

    /** @test */
    public function should_not_get_redirect_with_undefined_id()
    {
        $redirect = factory(RedirectUrls::class)->create()->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->get('api/admin/redirect/' . 0);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Riderrect_urls not found'
        ]);
    }

    /** @test */
    public function can_get_redirects_by_admin()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($redirect) {
            unset($redirect['created_at']);
            unset($redirect['updated_at']);
            return $redirect;
        })->toArray();

        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect/all');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $redirects
        ]);
    }

    /** @test */
    public function can_get_redirect_with_constraints()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $from_url_constraints = $redirects[0]->from_url;
        $to_url_constraints = $redirects[0]->to_url;

        $redirects = $redirects->map(function ($redirect) {
            unset($redirect['created_at']);
            unset($redirect['updated_at']);
            return $redirect;
        })->toArray();

        $constraints = '{"from_url":"' . $from_url_constraints . '", "to_url":"' . $to_url_constraints . '"}';

        $response = $this->call('GET', 'api/admin/redirect/all?constraints=' . $constraints);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$redirects[0]]
        ]);
    }

    /** @test */
    public function can_get_redirect_with_search()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($s) {
            unset($s['updated_at']);
            unset($s['created_at']);
            return $s;
        })->toArray();

        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect/all?search=' . $redirects[0]['from_url']);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$redirects[0]]
        ]);
    }

    /** @test */
    public function can_get_redirects_with_order_by()
    {
        $redirects = factory(RedirectUrls::class, 5)->create();

        $redirects = $redirects->map(function ($s) {
            unset($s['updated_at']);
            unset($s['created_at']);
            return $s;
        })->toArray();

        $order_by = '{"from_url":"desc"}';


        $listIds = array_column($redirects, 'id');
        array_multisort($listIds, SORT_ASC, $redirects);

        $listName = array_column($redirects, 'from_url');
        array_multisort($listName, SORT_DESC, $redirects);

        $response = $this->call('GET', 'api/admin/redirect/all?order_by=' . $order_by);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $redirects,
        ]);
    }

    /** @test */
    public function can_update_redirect_by_admin()
    {
        $redirect = factory(RedirectUrls::class)->create()->toArray();

        $new_data = factory(RedirectUrls::class)->make()->toArray();

        unset($new_data['created_at']);
        unset($new_data['updated_at']);

        $response = $this->put('api/admin/redirect/' . $redirect['id'], $new_data);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $new_data
        ]);
    }

    /** @test */
    public function should_not_update_redirect_with_undefined_id()
    {
        $redirect = factory(RedirectUrls::class)->create()->toArray();
        $new_data = factory(RedirectUrls::class)->make()->toArray();

        unset($new_data['created_at']);
        unset($new_data['updated_at']);

        $response = $this->put('api/admin/redirect/0', $new_data);

        $response->assertStatus(404);
    }

    /** @test */
    public function can_delete_redirect_by_admin()
    {
        $redirect = factory(RedirectUrls::class)->create()->toArray();

        unset($redirect['created_at']);
        unset($redirect['updated_at']);

        $response = $this->delete('api/admin/redirect/'.$redirect['id']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('redirect_urls', $redirect);
    }
}
