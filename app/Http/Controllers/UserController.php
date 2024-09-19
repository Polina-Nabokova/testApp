<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    /**
     * Create new user
     * @param UserRequest $request
     * @return success message
     */
    public function create(UserRequest $request) { 
        $user = new Users();
        $userData = $request->except('_token', 'photo');       
        $userData['photo'] = $user->uploadImage($request->photo);       
        $user->create($userData);
        
        return redirect()->route('users-list')->with('success', 'User has been created');
    }
    
    /**
     * Return all users
     * @param Request $request
     * @return type
     */
    public function getAll(Request $request) {
        $page = $request->has('page') ? $request->integer('page') : 1;
        $count = $request->has('count') ? $request->integer('count') : 6; 
        $users = (new Users())->getAll($count, $page);
        $users->appends(['count' => $count]);
        return view('users', [
            'users' => $users,
            'count' => $count
        ]);
    }
    
    /**
     * Create default users 
     * @return type
     */
    public function import() {
        ini_set('max_execution_time', 180); //3 minutes
        Users::factory()->count(1)->create();
        return redirect()->route('users-list')->with('success', 'Users has been created');
    }
    
    
     public function loadMore(Request $request) {
        $page = $request->integer('page');
        $count = $request->integer('count'); 
        $users = (new Users())->getAll($count , $page);
                
        $html = '';
        if($users->isNotEmpty()) {
            foreach($users as $key => $user) {
                $html .= view('includes.users', [
                    'user'  => $user,
                    'index' => ($count * ($users->currentPage() - 1)) + $key + 1
                ])->render();
            }
        }
        $count_show = ($count * ($users->currentPage() - 1)) + count($users);
        return [
            'html'     => $html,
            'lastPage' => $users->lastPage(),
            'show'     => $count_show
        ];
    }    
}
