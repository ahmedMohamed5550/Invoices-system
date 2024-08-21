<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{

    public function index(Request $request)
    {
    $roles = Role::orderBy('id','DESC')->paginate(5);
    return view('roles.index',compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array', // Ensure that it's an array of permission IDs
            'permission.*' => 'exists:permissions,id', // Validate each permission ID
        ]);

        // Create the role
        $role = Role::create(['name' => $request->input('name')]);

        // Sync permissions with the role
        $permissions = $request->input('permission');

        // Check if permissions exist before syncing
        $existingPermissions = Permission::whereIn('id', $permissions)->pluck('id')->toArray();

        if (count($existingPermissions) != count($permissions)) {
            return redirect()->back()
                ->withErrors(['permission' => 'Some permissions are invalid or do not exist.'])
                ->withInput();
        }

        $role->syncPermissions($existingPermissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }


    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id', // Ensure each permission ID exists
        ]);

        $role = Role::findOrFail($id); // Use findOrFail to handle non-existent roles

        // Update role name
        $role->name = $request->input('name');
        $role->save();

        // Retrieve the valid permissions
        $permissions = Permission::whereIn('id', $request->input('permission'))->pluck('id');

        // Sync permissions
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }


    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
