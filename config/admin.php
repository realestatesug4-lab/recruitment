<?php

return [
    // Super admin credentials — MUST be set in .env, never hardcoded
    'panel_domain' => env('ADMIN_PANEL_DOMAIN', 'admin.cranelinks.com'),

    'super_admin_email' => env('SUPER_ADMIN_EMAIL'),
    'super_admin_password' => env('SUPER_ADMIN_PASSWORD'),
];
