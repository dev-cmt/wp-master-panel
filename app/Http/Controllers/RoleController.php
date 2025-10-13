<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);

        return view('backend.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();

        // Group permissions by module (assuming names like 'view-users', 'edit-users')
        $groupedPermissions = $permissions->groupBy(function($item) {
            $parts = explode(' ', $item->name);
            array_shift($parts); // remove first word (action)
            return implode(' ', $parts); // remaining words = module
        });

        return view('backend.roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        // Create Role
        $role = Role::create(['name' => $request->name]);

        // Sync permissions (names are directly sent from form)
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();

        $groupedPermissions = $permissions->groupBy(function($item) {
            $parts = explode(' ', $item->name);
            array_shift($parts); // remove action
            return implode(' ', $parts); // module name
        });

        return view('backend.roles.edit', compact('role', 'groupedPermissions'));
    }


    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        // Update Role name
        $role->update(['name' => $request->name]);

        // Sync permissions (names are directly sent from form)
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
