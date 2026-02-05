<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller implements HasMiddleware
{

     public static function middleware(): array
    {
        return [
            new Middleware('permission:view permissions|role:super-admin', only: ['index']),
            new Middleware('permission:create permissions|role:super-admin', only: ['create','store']),
            new Middleware('permission:edit permissions|role:super-admin', only: ['edit','update']),
            new Middleware('permission:delete permissions|role:super-admin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);

        $data['permissions'] = $permissions;
        return view('permissions.index', $data);
    }
    public function create()
    {
        return view('permissions.create');
    }
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);
        if ($validator->passes()) {
            Permission::create([
                'name' => $request->name,
            ]);
            return redirect()->route('permissions.index')->with('success', 'permission added successfully');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }
    public function edit($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $data['permission'] = $permission;
        return view('permissions.edit', $data);
    }
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);
        $validator =  Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . ',id'
        ]);
        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success', 'permission Updated successfully');
        } else {
            return redirect()->route('permission.edit', $id)->withInput()->withErrors($validator);
        }
    }
    public function destroy(Request $request)
    {

        $permission = Permission::find($request->id);
        if ($permission == null) {
            session()->flash('error', 'Permission Not Found');

            return response()->json([
                'status' => false,
            ]);
        }
        $permission->delete();
        session()->flash('success', 'Permission Deleted Successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
