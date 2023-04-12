<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{   
    public function show($id){
        $user = User::findOrFail($id);
        $categories = Category::all();
        return view('user.show', ['user' => $user,
        'categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    public function edit(){
        $id = auth()->user()->id;
        $user = User::findOrFail($id);
        $categories = Category::all();
        return view('user.edit', ['user' => $user,
        'categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    
    public function changeData(Request $request){
        $id = auth()->user()->id;
        $user = User::findOrFail($id);
        if ($request->has('btn-password')) {
            $actualpassword = $request->input('actual-password');
            $password = $request->input('remember-password');
            $repeatpassword = $request->input('repeat-password');
                if(Hash::check($actualpassword, Auth::user()->password)){
                    if($password == $repeatpassword){
                        $user = User::updateUserPassword($id,$password);
                        session()->flash('status', 'Password changed succesfully.');
                    }else {
                        session()->flash('status', 'New password does not match');
                    }
            }else{
                session()->flash('status', 'Password is not correct');
            }

        }else if($request->has('btn-avatar')){
            if ($request->hasFile('avatar')) {
                $request->validate([
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $avatar = $request->file('avatar');
                $name = uniqid('profile_') . '.' . $avatar->extension();
                $path = 'images/profiles/';
                $avatar->move($path, $name);
                $user->avatar = $path . $name;
                $user->save();
            }
        }else if($request->has('btn-avatar-rm')){
            if ($user->avatar != 'images/profiles/default-avatar.jpg') {
                Storage::disk('public2')->delete($user->avatar);
                $user->avatar = 'images/profiles/default-avatar.jpg';
                $user->save();
            }
            return redirect()->route('user.edit');
        }
        return redirect()->route('user.edit');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('product.index'));
    }
}
