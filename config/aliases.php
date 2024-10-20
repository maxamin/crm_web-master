<?php

return [

    /* LegalController */
    '@legal' => '/legals',
    '@legal-view' => '/legal/view',
    '@legal-delete' => '/legal/delete',
    '@legal-update' => '/legal/update',
    '@legal-create' => '/legal/create',

    /* NaturalController */
    '@natural' => '/naturals',
    '@natural-view' => '/natural/view',
    '@natural-delete' => '/natural/delete',
    '@natural-update' => '/natural/update',
    '@natural-create' => '/natural/create',

    /* LeadController aliases */
    '@leads' => '/leads',
    '@leads-view' => '/lead/view',
    '@leads-create' => '/lead/create',
    '@leads-update' => '/lead/update',
    '@leads-delete' => '/lead/delete',
    '@leads-change-status' => 'lead/change-status',

    /* TasksController aliases */
    '@tasks' => '/tasks',
    '@tasks-view' => '/task/view',
    '@tasks-create' => '/task/create',
    '@tasks-update' => '/task/update',
    '@tasks-delete' => '/task/delete',
    '@tasks-close' => '/task/close',

    /* StatisticController */
    '@statistic-legal' => '/statistic/legal',
    '@statistic-natural' => '/statistic/natural',
    '@statistic-leads' => '/statistic/leads',
    '@statistic-tasks' => '/statistic/tasks',

    /* Dektrium */
    '@settings-profile' => '/user/settings/profile',
    '@settings-account' => '/user/settings/account',
    '@settings-avatar-upload' => '/user/settings/upload-avatar',

    '@security-logout' => '/user/security/logout',

    '@users-files' => '/files/users',
    '@users-files-app' => '@app/web/files/users',

    '@user-profile' => '/user/profile/show',

    '@admin-update-user' => '/user/admin/update',
    '@admin-delete-user' => '/user/admin/delete',

    '@admin-upload-avatar' => '/user/admin/upload-avatar',

    /* files */
    '@contacts-import-files' => '@app/web/files/contactsImport',
];