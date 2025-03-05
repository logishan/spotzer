<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class PagesController extends Controller
{
    
    public function login()
    {
        return view('pages.login');
    }
    public function register()
    {
        $departments = Department::all();
        return view('pages.register', compact('departments'));
    }
    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'department_id' => 'required|exists:departments,id',
            'terms' => 'required|accepted'
        ]);

        // Get default role (Employee) before creating user
        $defaultRole = Role::where('name', 'Employee')->first();
        if (!$defaultRole) {
            return back()->with('error', 'Default role not found. Please contact administrator.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'role_id' => $defaultRole->id, // Set the role_id during creation
        ]);

        // Assign department
        $user->departments()->attach($request->department_id);

        // Log the user in
        auth()->login($user);

        return redirect('/')->with('success', 'Registration successful! Welcome to the portal.');
    }
    public function forgot_password()
    {
        return view('pages.forgot-password');
    }
    public function lockscreen()
    {
        return view('pages.lockscreen');
    }
    public function error400()
    {
        return view('pages.error400');
    }
    public function error401()
    {
        return view('pages.error401');
    }
    public function error403()
    {
        return view('pages.error403');
    }
    public function error404()
    {
        return view('pages.error404');
    }
    public function error500()
    {
        return view('pages.error500');
    }
    public function error503()
    {
        return view('pages.error503');
    }
    public function file_manager()
    {
        return view('pages.file-manager');
    }
    public function filemanager_list()
    {
        return view('pages.filemanager-list');
    }
    public function filemanager_details()
    {
        return view('pages.filemanager-details');
    }
    public function file_attachments()
    {
        return view('pages.file-attachments');
    }
    public function shop()
    {
        return view('pages.shop');
    }
    public function shop_description()
    {
        return view('pages.shop-description');
    }
    public function cart()
    {
        return view('pages.cart');
    }
    public function add_product()
    {
        return view('pages.add-product');
    }
    public function wishlist()
    {
        return view('pages.wishlist');
    }
    public function checkout()
    {
        return view('pages.checkout');
    }
    public function blog()
    {
        return view('pages.blog');
    }
    public function blog_details()
    {
        return view('pages.blog-details');
    }
    public function blog_post()
    {
        return view('pages.blog-post');
    }
    public function profile()
    {
        return view('pages.profile');
    }
    public function editprofile()
    {
        return view('pages.editprofile');
    }
    public function email()
    {
        return view('pages.email');
    }
    public function emailservices()
    {
        return view('pages.emailservices');
    }
    public function mail_read()
    {
        return view('pages.mail-read');
    }
    public function gallery()
    {
        return view('pages.gallery');
    }
    public function about()
    {
        return view('pages.about');
    }
    public function services()
    {
        return view('pages.services');
    }
    public function faq()
    {
        return view('pages.faq');
    }
    public function terms()
    {
        return view('pages.terms');
    }
    public function invoice()
    {
        return view('pages.invoice');
    }
    public function notify_list()
    {
        return view('pages.notify-list');
    }
    public function pricing()
    {
        return view('pages.pricing');
    }
    public function switcher()
    {
        return view('pages.switcher');
    }
    public function emptypage()
    {
        return view('pages.emptypage');
    }
    public function construction()
    {
        return view('pages.construction');
    }
}

