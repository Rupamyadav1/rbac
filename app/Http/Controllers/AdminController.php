<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:create users', only: ['create']),
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:edit users', only: ['edit']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }
    public function index()
    {
        $users = Admin::latest()->paginate(10);

        $data['users'] = $users;
        return view('users.index', $data);
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $data['roles'] = $roles;
        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        } else {
            $user = new Admin();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success', 'User created successfully');
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $user = Admin::find($id);
        $hasRoles = $user->roles->pluck('id');
        $roles = Role::orderBy('name', 'asc')->get();
        //dd($hasRoles);
        $data['hasRoles'] = $hasRoles;
        $data['user'] = $user;
        $data['roles'] = $roles;
        return view('users.edit', $data);
    }

    public function update(Request $request, int $id)
    {
        $user = Admin::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        //$user->syncRoles($request->role);
        $user->syncRoles($request->role);
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(int $id)
    {
        $user = Admin::findOrFail($id);
        if ($user == null) {
            session()->flash('error', 'user not found ');
            return response()->json([
                'status' => false,
            ]);
        }
        $user->delete();
        session()->flash('success', 'User deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
