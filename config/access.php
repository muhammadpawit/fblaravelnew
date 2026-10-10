<?php

return [
    'permissions' => [
        'cmt.view' => ['label' => 'Lihat Master CMT', 'group' => 'Master CMT'],
        'cmt.create' => ['label' => 'Tambah Master CMT', 'group' => 'Master CMT'],
        'cmt.update' => ['label' => 'Ubah Master CMT', 'group' => 'Master CMT'],
        'cmt.delete' => ['label' => 'Hapus Master CMT', 'group' => 'Master CMT'],
        'jenis-po.view' => ['label' => 'Lihat Master Jenis PO', 'group' => 'Master Jenis PO'],
        'jenis-po.create' => ['label' => 'Tambah Master Jenis PO', 'group' => 'Master Jenis PO'],
        'jenis-po.update' => ['label' => 'Ubah Master Jenis PO', 'group' => 'Master Jenis PO'],
        'jenis-po.delete' => ['label' => 'Hapus Master Jenis PO', 'group' => 'Master Jenis PO'],
        'login-carousel.view' => ['label' => 'Lihat Login Carousel', 'group' => 'Login Carousel'],
        'login-carousel.create' => ['label' => 'Tambah Login Carousel', 'group' => 'Login Carousel'],
        'login-carousel.update' => ['label' => 'Ubah Login Carousel', 'group' => 'Login Carousel'],
        'login-carousel.delete' => ['label' => 'Hapus Login Carousel', 'group' => 'Login Carousel'],
        'access.manage' => ['label' => 'Kelola pengguna, role, permission, dan menu', 'group' => 'Administrasi'],
    ],
    'menu_routes' => [
        'dashboard' => ['label' => 'Dashboard', 'permission' => null],
        'master.cmt.index' => ['label' => 'Master CMT', 'permission' => 'cmt.view'],
        'master.jenis-po.index' => ['label' => 'Master Jenis PO', 'permission' => 'jenis-po.view'],
        'master.login-carousels.index' => ['label' => 'Login Carousel', 'permission' => 'login-carousel.view'],
        'access.users.index' => ['label' => 'Pengguna', 'permission' => 'access.manage'],
        'access.roles.index' => ['label' => 'Role & Permission', 'permission' => 'access.manage'],
        'access.permissions.index' => ['label' => 'Daftar Permission', 'permission' => 'access.manage'],
        'access.menus.index' => ['label' => 'Menu', 'permission' => 'access.manage'],
    ],
];
