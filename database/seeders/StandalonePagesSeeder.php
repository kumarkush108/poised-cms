<?php

namespace Database\Seeders;

use App\Cms\PageSectionBootstrapper;
use App\Cms\TemplateRegistry;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds a handful of real, admin-editable pages (Career, Support, Terms,
 * FAQ) built from the reusable templates added in the Page CRUD work —
 * these exist so the topbar's Career/Support/Terms/FAQs links resolve to
 * actual CMS pages instead of dead `#` links. Unlike PagesSeeder, these
 * are NOT system pages (is_system => false) — an admin can freely edit,
 * unpublish, or delete them, same as any page they'd create themselves.
 */
class StandalonePagesSeeder extends Seeder
{
    public function run(): void
    {
        $career = $this->makePage('career', 'Careers', 'career_page');
        $this->sectionFields($career, 'page_header', ['heading' => 'Careers at Poised Technology']);
        $this->sectionFields($career, 'content', [
            'heading' => 'Build the Future With Us',
            'body' => '<p>We are always looking for talented engineers, designers, and consultants to join our team. Explore current openings or reach out to tell us how you can contribute.</p>',
        ]);

        $support = $this->makePage('support', 'Support', 'support_page');
        $this->sectionFields($support, 'page_header', ['heading' => 'Support Center']);
        $this->sectionFields($support, 'content', [
            'heading' => 'How Can We Help?',
            'body' => '<p>Our support team is ready to help with technical issues, account questions, and anything else related to your Poised Technology services.</p>',
        ]);

        $terms = $this->makePage('terms', 'Terms & Conditions', 'terms_page');
        $this->sectionFields($terms, 'page_header', ['heading' => 'Terms & Conditions']);
        $this->sectionFields($terms, 'content', [
            'body' => '<p>These terms and conditions outline the rules and regulations for the use of Poised Technology\'s website and services. By accessing this website, you accept these terms in full.</p>',
        ]);

        $faq = $this->makePage('faq', 'Frequently Asked Questions', 'faq_page');
        $this->sectionFields($faq, 'page_header', ['heading' => 'Frequently Asked Questions']);
        $this->seedFaqItems($faq, [
            ['question' => 'What services does Poised Technology offer?', 'answer' => 'We provide software development, cloud infrastructure, data analytics, and EV charging solutions.'],
            ['question' => 'How do I get in touch with support?', 'answer' => 'Visit our Support page or use the contact form to reach our team directly.'],
            ['question' => 'Where are you located?', 'answer' => 'Our office address and contact details are listed in the site footer and Contact page.'],
        ]);
    }

    private function makePage(string $slug, string $title, string $template): Page
    {
        $page = Page::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'template' => $template,
                'is_system' => false,
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        PageSectionBootstrapper::run($page);

        return $page;
    }

    private function sectionFields(Page $page, string $sectionKey, array $fields): void
    {
        $section = $page->sections()->where('section_key', $sectionKey)->first();

        if (! $section) {
            return;
        }

        foreach ($fields as $key => $value) {
            $section->fields()->updateOrCreate(
                ['field_key' => $key],
                ['value' => $value, 'media_id' => null]
            );
        }
    }

    private function seedFaqItems(Page $page, array $items): void
    {
        $section = $page->sections()->where('section_key', 'faq')->first();

        if (! $section) {
            return;
        }

        $itemType = TemplateRegistry::itemSchema('faq')['item_type'] ?? 'faq-item';

        foreach ($items as $order => $fields) {
            $item = $section->items()->firstOrCreate(
                ['order_column' => $order],
                ['item_type' => $itemType, 'is_active' => true]
            );

            foreach ($fields as $key => $value) {
                $item->fields()->updateOrCreate(
                    ['field_key' => $key],
                    ['value' => $value, 'media_id' => null]
                );
            }
        }
    }
}
