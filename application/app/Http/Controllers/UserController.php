<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller {
    public function __construct() {
        $heading_name = 'Users';

    }

    public function index() {
        $heading_name = 'Users';

        $users = User::orderBy( 'id', 'DESC' )->get();
        return view( 'admin.users.index', compact( 'users', 'heading_name' ) );
    }

    public function create() {
        $heading_name = 'Users';

        $roles = Role::pluck( 'name', 'name' )->all();
        return view( 'admin.users.create', compact( 'roles', 'heading_name' ) );
    }

    public function store( Request $request ) {
        $this->validate( $request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ] );

        $input = $request->all();
        $input[ 'password' ] = Hash::make( $input[ 'password' ] );

        $user = User::create( $input );
        $user->assignRole( $request->input( 'roles' ) );

        return redirect()->route( 'users.index' )
        ->with( 'success', 'User created successfully' );
    }

    public function show( $id ) {
        $heading_name = 'Users';

        $user = User::find( $id );
        return view( 'admin.users.show', compact( 'user', 'heading_name' ) );
    }

    public function edit( $id ) {
        $heading_name = 'Users';

        $user = User::find( $id );
        $roles = Role::pluck( 'name', 'name' )->all();
        $userRole = $user->roles->pluck( 'name', 'name' )->all();

        return view( 'admin.users.edit', compact( 'user', 'roles', 'userRole', 'heading_name' ) );
    }

    public function update( request $request, $id ) {
        $this->validate( $request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ] );

        $input = $request->all();
        if ( !empty( $input[ 'password' ] ) ) {

            $input[ 'password' ] = Hash::make( $input[ 'password' ] );
        } else {
            $input = Arr::except( $input, array( 'password' ) );

        }

        $user = User::find( $id );
        $user->update( $input );
        DB::table( 'model_has_roles' )->where( 'model_id', $id )->delete();

        $user->assignRole( $request->input( 'roles' ) );

        return redirect()->route( 'users.index' )
        ->with( 'success', 'User updated successfully' );
    }

    public function destroy( Request $request, $id ) {
        User::find( $id )->delete();
        return redirect()->route( 'users.index' )
        ->with( 'success', 'User deleted successfully' );

    }

}
