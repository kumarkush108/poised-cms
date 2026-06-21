<?php

namespace Tests\Feature\Admin;

use App\Models\ContentRevision;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_products_index(): void
    {
        $this->get(route('admin.products.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_create_a_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'slug' => 'smart-charger',
            'title' => 'Smart Charger',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['slug' => 'smart-charger', 'title' => 'Smart Charger']);
    }

    public function test_product_creation_rejects_duplicate_slug(): void
    {
        $user = User::factory()->create();
        Product::create(['slug' => 'smart-charger', 'title' => 'Existing', 'status' => 'draft']);

        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'slug' => 'smart-charger',
            'title' => 'Duplicate',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_slug_cannot_be_changed_on_update(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'draft']);

        $response = $this->actingAs($user)->patch(route('admin.products.update', $product), [
            'title' => 'Updated Title',
            'status' => 'published',
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();
        $this->assertSame('smart-charger', $product->slug);
        $this->assertSame('Updated Title', $product->title);
        $this->assertSame('published', $product->status);
    }

    public function test_updating_a_product_records_a_revision(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'draft']);

        $this->assertSame(0, ContentRevision::where('revisionable_type', Product::class)->where('revisionable_id', $product->id)->count());

        $this->actingAs($user)->patch(route('admin.products.update', $product), [
            'title' => 'Updated Title',
            'status' => 'draft',
        ]);

        $this->assertSame(1, ContentRevision::where('revisionable_type', Product::class)->where('revisionable_id', $product->id)->count());
    }

    public function test_restoring_a_revision_brings_back_prior_title(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'First Title', 'status' => 'draft']);

        $this->actingAs($user)->patch(route('admin.products.update', $product), [
            'title' => 'First Title', 'status' => 'draft',
        ]);
        $firstRevision = ContentRevision::where('revisionable_id', $product->id)->latest()->first();

        $this->actingAs($user)->patch(route('admin.products.update', $product), [
            'title' => 'Second Title', 'status' => 'draft',
        ]);

        $this->actingAs($user)->post(route('admin.products.revisions.restore', [$product, $firstRevision]))
            ->assertRedirect(route('admin.products.edit', $product));

        $this->assertSame('First Title', $product->fresh()->title);
    }

    public function test_deleting_a_product_soft_deletes_it(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'published']);

        $this->actingAs($user)->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_product_category_can_be_created_and_assigned(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.product-categories.store'), [
            'slug' => 'ev-chargers', 'name' => 'EV Chargers',
        ])->assertSessionHasNoErrors();

        $category = ProductCategory::where('slug', 'ev-chargers')->first();
        $this->assertNotNull($category);

        $this->actingAs($user)->post(route('admin.products.store'), [
            'slug' => 'smart-charger', 'title' => 'Smart Charger', 'category_id' => $category->id, 'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertSame($category->id, Product::where('slug', 'smart-charger')->first()->category_id);
    }
}
