<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class UserProfile extends Controller
{
    public function index()
    {
        return view('admin.user-profile');
    }

    public function updateName(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $newName = null;
            if ($request->hasFile('avatar')) {
                $newName = $this->storeImage($request->file('avatar'));
                
                // Delete old avatar if exists
                if ($user->avatar && Storage::exists($user->avatar)) {
                    Storage::delete($user->avatar);
                }
            }

            $user->update([
                'name' => $request->full_name,
                'avatar' => $newName ?: $user->avatar,
            ]);
        return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Alert::error('error', 'Failed to update profile.');
            return back();
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            Alert::error('error', 'The old password is incorrect.');
            return back()->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    private function storeImage($image)
    {
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '-' . rand(1000, 1000000) . '.' . $ext;
        $path = $image->storeAs('avatars', $newName, 'public');
        
        return $path;
    }
}