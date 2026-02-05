<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware():array
     {
        return [
            new Middleware('permission:create roles',only:['create']),
            new Middleware('permission:view roles',only:['index']),
            new Middleware('permission:edit roles',only:['edit']),
            new Middleware('permission:delete roles',only:['destroy']),


        ];

     }
    public function index()
   
   
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);

        $data['roles'] = $roles;

        return view('roles.index', $data);
    }
    public function create()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->get();
        $data['permissions'] = $permissions;
        return view('roles.create', $data);
    }
    public function store(Request $request)
    {
        // dd($request->permission);
        $validator =  Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3',
            'permission'=>'required',
        ]);
        if ($validator->passes()) {

            $role = Role::create([
                'name' => $request->name,
            ]);
            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }


            return redirect()->route('roles.index')->with('success', 'role added successfully');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }
    public function edit($id)
    {
        $role = Role::find($id);
        $hasPermissions=$role->permissions->pluck('name');
                $permissions = Permission::orderBy('created_at', 'DESC')->get();

        $data['hasPermissions']=$hasPermissions;
        $data['permissions']=$permissions;
        $data['role'] = $role;
        return view('roles.edit', $data);
    }
    public function update($id,Request $request) {
         $role = Role::findOrFail($id);
         $validator =  Validator::make($request->all(),[
            'name'=>'required|unique:roles,name,'.$id.',id',
             'permission'=>'required',
        ]);
        if($validator->passes()){
            $role->name=$request->name;
            $role->save();
            if(!empty($request->permission)){
                $role->syncPermissions($request->permission);

            }
            else{
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success','Role updated successfully');
           
        }else{
        return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);

        }
    }
    public function destroy(Request $request)
    {
        $role = Role::find($request->id);

        if ($role == null) {
            session()->flash('error', 'Role Not Found');
            return response()->json([
                'status' => false,
            ]);
        }
        $role->delete();
        session()->flash('success', 'Role deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
