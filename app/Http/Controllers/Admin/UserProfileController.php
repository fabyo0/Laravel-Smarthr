<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        $title = 'user Profile';
        return view('backend.profile', compact(
            'title'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150|min:5',
            'username' => 'required|max:20|min:3',
            'email' => 'required|email',
            'avatar' => 'file|image|mimes:jpg,jpeg,png,gif',
            'password' => 'required'
        ]);

        $imageName = auth()->user()->avatar;
        if ($request->hasFile('avatar')) {
            $imageName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('storage/users'), $imageName);
        }
        auth()->user()->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'avatar' => $imageName,
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', "user info has been updated");
    }
}
