<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $categories = Category::all();
        $id = Auth::id();

        return view('user.show', [
            'user' => $user,
            'categories' => $categories,
            'options_order' => Order::$order_array
        ]);
    }
    public function edit()
    {
        $id = auth()->user()->id;
        $user = User::findOrFail($id);
        $categories = Category::all();
        return view('user.edit', [
            'user' => $user,
            'categories' => $categories,
            'options_order' => Order::$order_array
        ]);
    }

    public function changeData(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if ($request->has('btn-password')) {
            $validator = Validator::make($request->all(), [
                'current-password' => 'required|string|min:8|max:255',
                'new-password' => 'required|string|min:8|max:255|same:repeat-password',
                'repeat-password' => 'required|string|min:8|max:255|same:new-password',
            ], [
                'current-password.required' => 'The current password field is required.',
                'current-password.max' => 'The current password may not be greater than :max characters.',
                'current-password.min' => 'The current password must be at least :min characters.',
                
                'new-password.required' => 'The new password field is required.',
                'new-password.max' => 'The new password may not be greater than :max characters.',
                'new-password.min' => 'The new password must be at least :min characters.',
                'new-password.confirmed' => 'The new passwords does not match.',
    
                'repeat-password.required' => 'The new password field is required.',
                'repeat-password.max' => 'The new password may not be greater than :max characters.',
                'repeat-password.min' => 'The new password must be at least :min characters.',
                'repeat-password.same' => 'The new password and repeat password do not match.',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $actualpassword = $request->input('current-password');
            $password = $request->input('new-password');
            $repeatpassword = $request->input('repeat-password');
            $user->password = Hash::make($password);
            $user->save();
            session()->flash('status', 'Password changed succesfully.');
        } else if ($request->has('btn-avatar')) {
            if ($request->hasFile('avatar')) {
                $validator = Validator::make($request->all(), [
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ], [
                    'avatar.image' => 'The avatar must be an image file.',
                    'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
                    'avatar.max' => 'The avatar may not be greater than :max kilobytes in size.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $avatar = $request->file('avatar');
                $name = uniqid('profile_') . '.' . $avatar->extension();
                $path = 'images/profiles/';
                $avatar->move($path, $name);
                $user->avatar = $path . $name;
                $user->save();
            }
        } else if ($request->has('btn-avatar-rm')) {
            if ($user->avatar != 'images/profiles/default-avatar.jpg') {
                Storage::disk('public2')->delete($user->avatar);
                $user->avatar = 'images/profiles/default-avatar.jpg';
                $user->save();
            }
            return redirect()->route('user.edit');
        } else if ($request->has('btn-username')) {
            $username = $request->input('username');    
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|alpha_num|max:255|min:3|unique:users',
                ], [
                'name.required' => 'The username field is required.',
                'name.regex' => 'The username field format is invalid.',
                'name.max' => 'The username may not be greater than :max characters.',
                'name.min' => 'The username must be at least :min characters.',
                'name.unique' => 'The username has already been taken.'
            ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            $user->name = $request->input('name');
            $user->save();
            session()->flash('status', 'Username changed succesfully.');
        }
        return redirect()->route('user.edit');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('product.index'));
    }
}
