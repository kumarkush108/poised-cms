<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    // The testing CACHE_STORE is "array" (phpunit.xml), which — unlike the
    // database — is NOT reset between test methods by RefreshDatabase; it
    // persists for the whole PHPUnit process. Without this, cache-backed
    // logic keyed by request content or a reused model ID (e.g. the
    // form-submission dedup guard, or RBAC's per-admin permission cache)
    // can leak stale state from an earlier, unrelated test into a later
    // one that happens to reuse the same key.
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }
}
