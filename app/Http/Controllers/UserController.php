<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    public function create(UserRequest $request) {
        if($request->has('photo')) {
            $user = new Users();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->position_id = $request->input('position_id');

            $photoName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $photoName);

            $user->photo = 'images/'.$photoName;
            $user->save();

        } else {
            return redirect()->route('users')->with('error', 'User hasn"t been created, please try later');
        }
       return redirect()->route('users')->with('success', 'User has been created');
    }

    public function edit($id, Request $request) {
        $users = new Users();
        $user = $users->find($id);
        if($user) {
            return view('user_form_edit', [
                'user_data' => $user,
                'positions' => DB::table('positions')->get()
            ]);
        }

        return redirect('/users');
    }

    public function update(UserUpdateRequest $request) {
        return redirect()->back()->with('success', 'User has been updated');
    }

    public function delete($id) {
        //User::delete($id);
        return redirect()->back()->with('success', 'User has been deleted');
    }


    public function getAll(Request $request) {
        $page = $request->has('page') ? $request->integer('page') : 1;
        $count = $request->has('count') ? $request->integer('count') : 6;         
        return view('users', [
            'users' => (new Users())->getAll($count, $page)
        ]);
    }

}
