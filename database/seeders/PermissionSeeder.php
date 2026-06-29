<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * module => [group, label, [actions]]. Mirrors this app's real admin
     * controllers/routes one-to-one — there is deliberately no entry for a
     * CMS section type (hero, testimonials, faq, gallery, ...) since those
     * are configured once in config/cms/templates.php and have no dedicated
     * controller/route of their own to gate.
     */
    private const MODULES = [
        'dashboard' => ['Dashboard', 'Dashboard', ['view']],
        'pages' => ['Content', 'Pages', ['view', 'create', 'edit', 'delete', 'publish']],
        'media' => ['Content', 'Media Library', ['view', 'upload', 'edit', 'delete']],
        'products' => ['Content', 'Products', ['view', 'create', 'edit', 'delete', 'publish']],
        'product_categories' => ['Content', 'Product Categories', ['view', 'create', 'edit', 'delete']],
        'blogs' => ['Content', 'Blog Posts', ['view', 'create', 'edit', 'delete', 'publish']],
        'blog_categories' => ['Content', 'Blog Categories', ['view', 'create', 'edit', 'delete']],
        'news' => ['Content', 'News Articles', ['view', 'create', 'edit', 'delete', 'publish']],
        'news_categories' => ['Content', 'News Categories', ['view', 'create', 'edit', 'delete']],
        'menus' => ['Site', 'Menus', ['view', 'edit']],
        'settings' => ['Site', 'Settings', ['view', 'edit']],
        'contact_messages' => ['Site', 'Contact Messages', ['view', 'edit']],
        'admins' => ['Administration', 'Admins', ['view', 'create', 'edit', 'delete']],
    ];

    public function run(): void
    {
        foreach (self::MODULES as $module => [$group, $moduleLabel, $actions]) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['module' => $module, 'action' => $action],
                    ['label' => ucfirst($action)." {$moduleLabel}", 'group' => $group]
                );
            }
        }
    }
}
