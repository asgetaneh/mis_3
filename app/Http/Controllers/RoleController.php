<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('list', Role::class);

        $search = $request->get('search', '');
        $roles = Role::where('name', 'like', "%{$search}%")->paginate(10);

        return view('app.roles.index')
            ->with('roles', $roles)
            ->with('search', $search);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::all();

        return view('app.roles.create')->with('permissions', $permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $this->authorize('create', Role::class);

        $data = $this->validate($request, [
            'name' => 'required|unique:roles|max:32',
            // 'permissions' => 'array',
        ]);

        $role = Role::create($data);

        // $permissions = Permission::find($request->permissions);
        // $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index', $role->id)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $this->authorize('view', Role::class);

        return view('app.roles.show')->with('role', $role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        $this->authorize('update', $role);

        $permissions = Permission::all();

        return view('app.roles.edit')
            ->with('role', $role)
            ->with('permissions', $permissions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        $data = $this->validate($request, [
            'name' => 'required|max:32|unique:roles,name,'.$role->id,
            // 'permissions' => 'array',
        ]);

        $role->update($data);

        // $permissions = Permission::find($request->permissions);
        // $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index')
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function rolePermissions(Request $request, Role $role)
    {
        $currentRolePermissions = Role::find($role->id)->permissions;
        // dd($currentRolePermissions);

        $currentPermissionArray = [];
        if($currentRolePermissions){
            foreach($currentRolePermissions as $permission){
                array_push($currentPermissionArray, $permission->id);
            }
        }

        $permissions = Permission::whereNotIn('id', $currentPermissionArray)->get();
        // dd($permissions);

        return view('app.roles.role-permissions', compact('permissions', 'currentRolePermissions', 'role'));
    }

    public function roleUsers(Request $request, Role $role)
    {
        // $user = Auth::user();
        // if (!$user->can('role.user') && !$user->can('role.*')) {
        //     return abort(403);
        // }

        $role = Role::where('id', $role->id)->first();
        $rolUserID = [];

        $all_users = User::whereHas(
            'roles', function($q) use ($role){
                $q->where('name', $role->name);
            }
        )->get();

        foreach ($all_users as $key => $user) {
            array_push($rolUserID, $user->id);
        }
        $users = User::whereNotIn('id', $rolUserID)->get();

        return view('app.roles.role-users', compact('role', 'users', 'all_users'));
    }

    public function rolePermissionsUpdate(Request $request)
    {
        // dd($request->all());

        $role = Role::find($request->role_id);

        $rolePermissions = $role->permissions()->get();
        // dd($rolePermissions);

        if ($request->get('permission')) {
            $permissionsUnique = array_unique($request->permission);
            $permissions = Permission::whereIn('id', $permissionsUnique)->get();

            foreach ($rolePermissions as $key => $permission) {
                if (!in_array($permission->id, $permissionsUnique)) {
                    $role->revokePermissionTo(Permission::find($permission->id));
                }
            }
            foreach ($permissions as $key => $permission) {
                $role->givePermissionTo(Permission::find($permission));
            }
        } else {
            foreach ($role->permissions()->get() as $key => $permission) {
                $role->revokePermissionTo($permission);
            }
        }

        return redirect()
            ->back()
            ->withSuccess(__('crud.common.updated'));
    }

    public function roleUsersUpdate(Request $request)
    {
        // $user = Auth::user();
        // if (!$user->can('role.user') && !$user->can('role.*')) {
        //     return abort(403);
        // }

        $role = Role::where('id', $request->get('role_id'))->first();
        $all_users = User::whereHas(
            'roles', function($q) use ($role){
                $q->where('name', $role->name);
            }
        )->get();

        if ($request->get('user')) {
            $arr_unique = array_unique($request->get('user'));
            $users = User::whereIn('id',$arr_unique)->get();
            foreach ($all_users as $key => $value) {
                if (!in_array($value->id, $arr_unique)) {
                    $value->removeRole($role->name);
                }
            }
            foreach ($users as $key => $user) {
                $user->assignRole($role->name);
            }
        }else{
            foreach ($all_users as $key => $value) {
                $value->removeRole($role->name);
            }
        }
        return redirect()->back()->withSuccess(__('crud.common.updated'));
    }
}
