<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clean up all roles, permissions, and users
        Role::query()->delete();
        Permission::query()->delete();
        User::query()->delete();

        parent::tearDown();
    }
    public function test_user_with_admin_role_can_access_admin_route()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        $user->assignRole('admin');
        $this->actingAs($user)
            ->get('/api/admin')
            ->assertStatus(200);
    }

    public function test_user_without_role_cannot_access_admin_route()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/api/admin')
            ->assertStatus(403);
    }

    public function test_assign_roles_to_user()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);

        $this->actingAs($user)
            ->postJson("/api/users/{$user->id}/roles", [
                'roles' => [$role->name],
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Roles assigned successfully',
            ]);

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_detach_roles_from_user()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor']);

        // Assign role
        $user->assignRole($role->name);

        // Detach role
        $this->actingAs($user)
            ->postJson("/api/users/{$user->id}/detach-roles", [
                'roles' => [$role->name],
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Roles detached successfully',
            ]);

        $this->assertFalse($user->hasRole('editor'));
    }


    public function test_assign_permissions_to_role()
    {
        $role = Role::create(['name' => 'super admin']);
        $permissions = [
            Permission::create(['name' => 'edit post']),
            Permission::create(['name' => 'delete post']),
            Permission::create(['name' => 'view post']),
        ];

        $this->actingAs(\App\Models\User::factory()->create()) // Assume an admin user
            ->postJson("/api/roles/{$role->id}/permissions", [
                'permissions' => array_map(fn($permission) => $permission->name, $permissions),
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Permissions assigned successfully',
            ]);

        foreach ($permissions as $permission) {
            $this->assertTrue($role->hasPermissionTo($permission->name));
        }
    }


    public function test_detach_permissions_from_role()
    {
        $role = Role::create(['name' => 'editor']);
        $permissions = [
            Permission::create(['name' => 'create post']),
            Permission::create(['name' => 'publish post']),
            Permission::create(['name' => 'update post']),
        ];

        // Assign permissions first
        $role->givePermissionTo(array_map(fn($permission) => $permission->name, $permissions));

        // Detach permissions
        $this->actingAs(\App\Models\User::factory()->create()) // Assume an admin user
            ->postJson("/api/roles/{$role->id}/detach-permissions", [
                'permissions' => array_map(fn($permission) => $permission->name, $permissions),
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Permissions detached successfully',
            ]);

        foreach ($permissions as $permission) {
            $this->assertFalse($role->hasPermissionTo($permission->name));
        }
    }
}
