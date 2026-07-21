<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_only_shows_published_products(): void
    {
        Product::create(['slug' => 'published-one', 'title' => 'Published One', 'status' => 'published']);
        Product::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Published One');
        $response->assertDontSee('Draft One');
    }

    public function test_draft_product_detail_page_returns_404(): void
    {
        $product = Product::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $this->get(route('products.show', $product->slug))->assertNotFound();
    }

    public function test_published_product_detail_page_shows_content(): void
    {
        $product = Product::create([
            'slug' => 'smart-charger', 'title' => 'Smart Charger',
            'short_description' => 'A fast charger.', 'status' => 'published',
            'features' => [['icon' => 'bi-lightning', 'title' => 'Fast Charging', 'description' => 'Quick charge']],
            'specifications' => [['label' => 'Power', 'value' => '22kW']],
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertOk();
        $response->assertSee('Smart Charger');
        $response->assertSee('A fast charger.');
        $response->assertSee('Fast Charging');
        $response->assertSee('22kW');
    }

    public function test_products_can_be_filtered_by_category(): void
    {
        $chargers = ProductCategory::create(['slug' => 'chargers', 'name' => 'Chargers']);
        $cables = ProductCategory::create(['slug' => 'cables', 'name' => 'Cables']);

        Product::create(['slug' => 'charger-a', 'title' => 'Charger A', 'category_id' => $chargers->id, 'status' => 'published']);
        Product::create(['slug' => 'cable-a', 'title' => 'Cable A', 'category_id' => $cables->id, 'status' => 'published']);

        $response = $this->get(route('products.index', ['category' => 'chargers']));

        $response->assertOk();
        $response->assertSee('Charger A');
        $response->assertDontSee('Cable A');
    }

    public function test_products_can_be_searched_by_title(): void
    {
        Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'published']);
        Product::create(['slug' => 'cable-a', 'title' => 'Cable A', 'status' => 'published']);

        $response = $this->get(route('products.index', ['search' => 'Smart']));

        $response->assertOk();
        $response->assertSee('Smart Charger');
        $response->assertDontSee('Cable A');
    }

    public function test_product_inquiry_form_creates_a_contact_message(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->post(route('products.inquiry'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'I am interested in this product.',
            'form_rendered_at' => encrypt(time() - 5),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Doe',
            'source_page' => 'product-inquiry',
        ]);
    }
}
