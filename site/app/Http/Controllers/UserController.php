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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ValidationMessages;

class UserController extends Controller
{
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $categories = Category::all();
            $id = Auth::id();
    
            return view('user.show', [
                'user' => $user,
                'categories' => $categories,
                'options_order' => Order::$order_array
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('user.404');
        }
    }
    public function edit()
    {
        if(auth()->user()){
            $id = auth()->user()->id;
            $user = User::findOrFail($id);
        }else{
            return redirect()->route('login.index');
        }
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
            ], ValidationMessages::userValidationMessages());
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $actualpassword = $validator->validated()['current-password'];
            $password = $validator->validated()['new-password'];
            $repeatpassword = $validator->validated()['repeat-password'];
            $user->password = Hash::make($password);
            $user->save();
            session()->flash('status', 'Password changed succesfully.');
        } else if ($request->has('btn-avatar')) {
            if ($request->hasFile('avatar')) {
                $validator = Validator::make($request->all(), [
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ], ValidationMessages::userValidationMessages());

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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|alpha_num|max:255|min:3|unique:users',
                ], ValidationMessages::userValidationMessages());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            $user->name = $validator->validated()['name'];
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
