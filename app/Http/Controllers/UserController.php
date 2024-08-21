<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $data = User::latest()->paginate(5);
        return view('users.index',compact('data'));
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required', // Use 'confirmed' to validate against 'confirm-password'
            'roles_name' => 'required|array' // Ensure roles_name is an array and required
        ]);

        // Prepare the input data
        $input = $request->all();
        $input['password'] = Hash::make($input['password']); // Hash the password

        // Create the user
        $user = User::create($input);

        // Assign roles to the user
        $user->assignRole($request->input('roles_name')); // 'roles_name' from form

        // Redirect to users index with a success message
        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles_name' => 'required|array',
            'roles_name.*' => 'exists:roles,name', // Validate each role exists in the roles table
        ]);

        $input = $request->except('confirm-password'); // Exclude confirm-password from the input

        // Handle password update if provided
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // Remove password if not provided to avoid updating it
            $input = Arr::except($input, ['password']);
        }

        // Find the user and update details
        $user = User::find($id);
        $user->update($input);

        // Remove existing roles and assign new ones
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles_name'));

        return redirect()->route('users.index')
            ->with('success', 'تم التحديث بنجاح');
    }


    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','تم الحذف بنجاح');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        dd($notifications);

        return view('layouts.main-header', compact('notifications'));
    }
}
