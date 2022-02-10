<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordCheckRequest;
use App\Rules\CheckOldPassword;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('admin.profile.password.edit');

    }// end of getChangePassword

    public function update(PasswordCheckRequest $request)
    {

        $request->merge(['password' => bcrypt($request->password)]);

        auth()->user()->update($request->all());

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.home');

    }// end of postChangePassword

}//end of controller
