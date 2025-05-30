<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;

class RoleController extends Controller {
    function __construct() {
         $this->middleware( 'permission:role-list|role-create|role-edit|role-delete', [ 'only' => [ 'index', 'store' ] ] );
         $this->middleware( 'permission:role-create', [ 'only' => [ 'create', 'store' ] ] );
         $this->middleware( 'permission:role-edit', [ 'only' => [ 'edit', 'update' ] ] );
         $this->middleware( 'permission:role-delete', [ 'only' => [ 'destroy' ] ] );
    }

    public function index( Request $request ): View {
        $heading_name = 'Role & Previllages'; 
        $roles = Role::orderBy( 'id', 'DESC' )->get();
        return view( 'admin.roles.index', compact( 'roles','heading_name' ) );
    }

    public function create() {
        $heading_name = 'Role & Previllages'; 
        $permission = Permission::get();
        return view( 'admin.roles.create', compact( 'permission','heading_name' ) );
    }

    public function store( Request $request ) {
        $this->validate( $request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ] );

        $role = Role::create( [ 'name' => $request->input( 'name' ) ] );
        $role->syncPermissions( $request->input( 'permission' ) );

        return redirect()->route( 'roles.index' )
        ->with( 'success', 'Role created successfully' );
    }

    public function show( $id ) {
        $heading_name = 'Role & Previllages'; 
        $role = Role::find( $id );
        $rolePermissions = Permission::join( 'role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id' )
        ->where( 'role_has_permissions.role_id', $id )
        ->get();

        return view( 'admin.roles.show', compact( 'role', 'rolePermissions','heading_name','heading_name' ) );
    }

    public function edit( $id ) {
        $heading_name = 'Role & Previllages'; 
        $role = Role::find( $id );
        $permission = Permission::get();
        $rolePermissions = DB::table( 'role_has_permissions' )->where( 'role_has_permissions.role_id', $id )
        ->pluck( 'role_has_permissions.permission_id', 'role_has_permissions.permission_id','role_has_permissions.id' )
        ->all();

        return view( 'admin.roles.edit', compact( 'role', 'permission', 'rolePermissions','heading_name' ) );
    }

    public function update( request $request, $id ) {
      
        $this->validate( $request, [
            'name' => 'required',
            'permission' => 'required',
        ] );

        $role = Role::find( $id );
        $role->name = $request->input( 'name' );
        $role->save();

        $role->syncPermissions( $request->input( 'permission' ) );

        return redirect()->route( 'roles.index' )
        ->with( 'success', 'Role updated successfully' );
    }
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
