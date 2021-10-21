<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\UserAddRequest;
use App\Http\Requests\admin\users\UserUpdateRequest;
use App\Models\User;
    
class UserController extends Controller
{
    private $keyForLang = ['key' => 'کاربر'];

    public function index()
    {
        $users = User::paginate(10);
        return view('frontend.panel.users.users',['users' => $users]);
    }

    public function add_form()
    {
        // $users = User::paginate(10);
        return view('frontend.panel.users.users-add');
    }

    public function added(UserAddRequest $request)
    {
        $validData = $request->validated();

        $createdUser = User::create([
            'name'   => $validData['name'],
            'email'  => $validData['email'],
            'mobile' => $validData['mobile'],
            'role'   => $validData['role'],
        ]);

        if(!$createdUser){
            return back()->with('failed', __('conditions.failed_add' , $this->keyForLang) );
        }
        return back()->with('success', __('conditions.success_add' , $this->keyForLang) );

    }

    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('frontend.panel.users.edit', compact('user') );
    }

    public function update(UserUpdateRequest $request , $user_id )
    {
        $validData = $request->validated();
        $user = User::findOrFail($user_id);

        $user->update([
            'name'   => $validData['name'],
            'email'  => $validData['email'],
            'mobile' => $validData['mobile'],
            'role'   => $validData['role'],
        ]);

        if(!$user){
            return back()->with('failed', __('conditions.failed_update' , $this->keyForLang) );
        }

        return back()->with('success' , __('conditions.success_update' , $this->keyForLang) );
    }

    public function delete($user_id)
    {
        User::findOrFail($user_id)->delete();
        return back()->with('success', __('conditions.success_delete' , $this->keyForLang));
    }

}
