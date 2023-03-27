<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index($id){
        $user = User::findOrFail($id);
        $categories = Category::all();
        return view('user.index', ['user' => $user,
        'id' => $id,
        'categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    
    public function changeData(Request $request, $id){
        $actualpassword = $request->input('actual-password');
        $password = $request->input('remember-password');
        $repeatpassword = $request->input('repeat-password');
        $user = User::findOrFail($id);
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
        return redirect()->route('user.index',['id' => $id]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('product.index'));
    }
}
