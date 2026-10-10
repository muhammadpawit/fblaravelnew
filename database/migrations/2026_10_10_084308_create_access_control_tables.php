<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('label', 150);
            $table->string('group', 100);
            $table->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->string('route_name', 150)->unique();
            $table->string('group', 100);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('permission_id')->nullable()->constrained()->restrictOnDelete();
            $table->timestamps();
        });

        $now = now();
        $adminId = DB::table('roles')->insertGetId(['name' => 'Administrator', 'slug' => 'administrator', 'is_system' => true, 'created_at' => $now, 'updated_at' => $now]);
        $operatorId = DB::table('roles')->insertGetId(['name' => 'Operator', 'slug' => 'operator', 'is_system' => false, 'created_at' => $now, 'updated_at' => $now]);
        $viewerId = DB::table('roles')->insertGetId(['name' => 'Viewer', 'slug' => 'viewer', 'is_system' => true, 'created_at' => $now, 'updated_at' => $now]);
        $permissions = [
            'cmt.view' => ['label' => 'Lihat Master CMT', 'group' => 'Master CMT'],
            'cmt.create' => ['label' => 'Tambah Master CMT', 'group' => 'Master CMT'],
            'cmt.update' => ['label' => 'Ubah Master CMT', 'group' => 'Master CMT'],
            'cmt.delete' => ['label' => 'Hapus Master CMT', 'group' => 'Master CMT'],
            'jenis-po.view' => ['label' => 'Lihat Master Jenis PO', 'group' => 'Master Jenis PO'],
            'jenis-po.create' => ['label' => 'Tambah Master Jenis PO', 'group' => 'Master Jenis PO'],
            'jenis-po.update' => ['label' => 'Ubah Master Jenis PO', 'group' => 'Master Jenis PO'],
            'jenis-po.delete' => ['label' => 'Hapus Master Jenis PO', 'group' => 'Master Jenis PO'],
            'access.manage' => ['label' => 'Kelola pengguna, role, permission, dan menu', 'group' => 'Administrasi'],
        ];
        $permissionIds = [];
        foreach ($permissions as $name => $attributes) {
            $permissionIds[$name] = DB::table('permissions')->insertGetId($attributes + ['name' => $name, 'created_at' => $now, 'updated_at' => $now]);
            DB::table('permission_role')->insert(['role_id' => $adminId, 'permission_id' => $permissionIds[$name]]);
            if ($name !== 'access.manage' && ! str_ends_with($name, '.delete')) {
                DB::table('permission_role')->insert(['role_id' => $operatorId, 'permission_id' => $permissionIds[$name]]);
            }
        }
        $menus = [
            ['label' => 'Dashboard', 'route_name' => 'dashboard', 'group' => 'Utama', 'sort_order' => 0, 'permission_id' => null],
            ['label' => 'Master CMT', 'route_name' => 'master.cmt.index', 'group' => 'Master Data', 'sort_order' => 10, 'permission_id' => $permissionIds['cmt.view']],
            ['label' => 'Master Jenis PO', 'route_name' => 'master.jenis-po.index', 'group' => 'Master Data', 'sort_order' => 20, 'permission_id' => $permissionIds['jenis-po.view']],
            ['label' => 'Pengguna', 'route_name' => 'access.users.index', 'group' => 'Administrasi', 'sort_order' => 30, 'permission_id' => $permissionIds['access.manage']],
            ['label' => 'Role & Permission', 'route_name' => 'access.roles.index', 'group' => 'Administrasi', 'sort_order' => 40, 'permission_id' => $permissionIds['access.manage']],
            ['label' => 'Daftar Permission', 'route_name' => 'access.permissions.index', 'group' => 'Administrasi', 'sort_order' => 50, 'permission_id' => $permissionIds['access.manage']],
            ['label' => 'Menu', 'route_name' => 'access.menus.index', 'group' => 'Administrasi', 'sort_order' => 60, 'permission_id' => $permissionIds['access.manage']],
        ];
        foreach ($menus as $menu) {
            DB::table('menus')->insert($menu + ['is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        Schema::table('users', function (Blueprint $table) use ($viewerId) {
            $table->foreignId('role_id')->default($viewerId)->constrained()->restrictOnDelete();
        });
        DB::table('users')->where('hak_akses', 1)->update(['role_id' => $adminId]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
        Schema::dropIfExists('menus');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
