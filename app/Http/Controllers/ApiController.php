<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{    
    public $success = 200;
    public $not_found = 404;
    public $validation = 422;
    
    public function createUser(UserRequest $request) {
        if ($user->tokenCan('server:update')) {
            $user->tokens()->delete();
    
        }
    }
    
    /**
     * Return users data divided into pages and sorted by Id in the ascending order
     * @param Request $request ['page', 'count']
     * @return json object
     */
    public function getUsers(Request $request){
        $status_code = $this->success;
        $response = [];
        $page = $request->integer('page');
        $count = $request->integer('count');
        // validate request params       
        if(($request->has('count') && !$count) || ($request->has('page') && !$page)) {
            $status_code = $this->validation;            
            if($request->has('count') && !$count) {
               $response = $this->getResponseArr($status_code, 'count');
            }           
            if($request->has('page') && !$page) {
               $response = $this->getResponseArr($status_code, 'page');                
            }
        } else {
            $page = $page ? $page : 1;
            $count = $count ? $count : 6;           
           
            $users = (new Users())->getAll($count, $page);
            
            if($users->count()) {
                $response = [
                    'success'     => true,
                    'page'        => $page,
                    'total_pages' => $users->lastPage(),
                    'total_users' => $users->total(),
                    'count'       => $count,
                    'links'       => [
                        'next_url' => $users->nextPageUrl(),
                        'prev_url' => $users->previousPageUrl()
                    ],
                    'users' => $users->items()
                ];
            } else {
                $status_code = $this->not_found;
                $response = $this->getResponseArr($status_code, 'Page');
            } 
        }       
        
        return response()->json($response,  $status_code);
    }
    
    /**
     * Return information about user by his id
     * @param int $id
     * @return json object
     */
    public function getOneUser($id) { 
        if(!is_numeric($id)) {
            $status_code = $this->validation;
            $response = $this->getResponseArr($status_code, 'user');
        } else {
            $user = (new Users())->find($id);        
            if($user) {
                $status_code = $this->success;
                $response = $this->getResponseArr($status_code);
                $user['photo'] = asset('') . $user['photo'];
                $user['position'] = DB::table('positions')->where('id', $user['position_id'])->value('name');
                unset($user['registration_timestamp']);
                $response['user'] = $user;           
            } else {
                $status_code = $this->not_found;
                $response = $this->getResponseArr($status_code, 'User');
            } 
        }
        
        return response()->json($response,  $status_code);
    }
    
    /**
     * Returns a list of all available users positions
     * @return json object
     */
    public function getPositions() {
        $positions = DB::table('positions')->get();
        if($positions){
            $status_code = $this->success;
            $response = $this->getResponseArr($status_code);
            $response['positions'] = $positions;
        } else {
            $status_code = $this->not_found;
            $response = $this->getResponseArr($status_code, 'Positions');
        }
        return response()->json($response,  $status_code);
    }

    /**
     * Returns a token that is required to register a new user
     * @return json object
     */
    public function getUserToken() {
        $status_code = $this->success;
        $response = $this->getResponseArr($status_code);
        $response['token'] = $user->createToken('token-name', ['*'], 40)->plainTextToken;
        return response()->json($response,  $status_code);
    }

    /**
     * Return array with status and message
     * @param int $status
     * @param str $atrr
     * @return array ['success','message', 'fails']
     */
    public function getResponseArr($status, $atrr = null) {
        $response['success'] = $status == $this->success ? true : false;
        if($status == $this->not_found) {
             $response['message'] = $atrr . ' not found';
        }
        if($status == $this->validation) {
            $response['message'] = 'Validation failed' ;
            if($atrr) {              
                $response['fails'][$atrr][] = $atrr == 'page' ? 'The page must be at least 1.' : "The $atrr must be an integer.";                 
            }
        }        
        return $response;
    }
}
