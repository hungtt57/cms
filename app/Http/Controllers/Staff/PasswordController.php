<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Remote\Remote;
use App\Models\Enterprise\ProductReview\Product;
use App\Models\Enterprise\ProductReview\Review;

use Carbon\Carbon;
use Hash;

class PasswordController extends Controller
{
    public function index(Request $request)
    {
        return view('staff.change_password');
    }

    public function change(Request $request)
    {
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $user = auth()->guard('staff')->user();

        if (!Hash::check($oldPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors('Mật khẩu cũ không chính xác')
            ;
        }

        if (Hash::check($newPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors('Mật khẩu mới không được giống mật khẩu cũ')
            ;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()
            ->back()
            ->withSuccess('Thay đổi mật khẩu thành công')
        ;
    }
}
