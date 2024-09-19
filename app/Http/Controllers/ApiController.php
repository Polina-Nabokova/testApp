<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;


class ApiController extends Controller
{   
    protected $responce_status;
    protected $success = 200;
    protected $not_found = 404;
    protected $validation = 422;
    protected $expired = 401;
   
    /**
     * Create new user using token
     * @param Request $request
     * @return json object
     */
    public function createUser(Request $request){
        $is_token = $this->checkToken($request->header('token'));
        if(!$is_token['success']) {
             return response()->json($is_token,  $this->responce_status);   
        }        
        $validate = Validator::make($request->all(), Users::getValidationRules());
        if ($validate->fails()) {
            $response = $this->getResponseArr($this->validation);
            $response['fails'] = $validate->errors();
            return response()->json($response,  $this->validation);   
        } 
        // token can be used only one time
        DB::table('personal_access_tokens')->delete($is_token['token_id']);
        
        $user = new Users();
        $userData = $request->except('_token', 'photo');       
        $userData['photo'] = $user->uploadImage($request->photo);       
        $new_user = $user->create($userData);
        $this->responce_status = $this->success;
        $response = $this->getResponseArr($this->success);
        $response["user_id"] = $new_user->id;
        $response["message"] = "New user successfully registered";                       
                      
        return response()->json($response,  $this->responce_status);        
    }
    
    /**
     * Return users data divided into pages and sorted by Id in the ascending order
     * @param Request $request ['page', 'count']
     * @return json object
     */
    public function getUsers(Request $request){
        // validate request params       
        $validate = Validator::make($request->all(), Users::getValidationRules());
        if ($validate->fails()) {
            $response = $this->getResponseArr($this->validation);
            $response['fails'] = $validate->errors();
            return response()->json($response,  $this->validation);   
        }  
        $page = $request->integer('page') ? $request->integer('page') : 1;
        $count = $request->integer('count') ? $request->integer('count') : 6;      
        $users = (new Users())->getAll($count, $page);
        if(!$users->count()) {
            return response()->json($this->getResponseArr($this->not_found, 'Page'),  $this->not_found);
        }            
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
        
        return response()->json($response,  $this->success);
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
        
        return response()->json($response, $status_code);
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
        $time = now()->add('+40 minutes'); // the token is valid for 40 minutes      
        $user = new Users();
        $user->id = 0; // create token without creating user
        $token = $user->createToken('registration_token', ['read:limited'], $time); 
        $response = $this->getResponseArr($this->success);
        $response['token'] = base64_encode($token->plainTextToken);   
        return response()->json($response,  $this->success);
    }
    
     /**
     * Check if exists and not expire
     * @param str $req_token
     * @return array [success, message, token_id]
     */
    public function checkToken($req_token) {  
        if(!$req_token) {             
            return $this->getResponseArr($this->validation, 'token'); 
        }
        $hash = $this->decodeToken($req_token);
        $exist_token = DB::table('personal_access_tokens')->where('token', '=', $hash)->first(); 
        if(!$exist_token) {
            $response = $this->getResponseArr($this->not_found, 'Token');
            $response['message'] = 'Invalid token. Try to get a new one by the method GET api/token.';
            $this->responce_status = $this->not_found;
        } elseif(Carbon::parse($exist_token->expires_at) < Carbon::now()){
             $response = $this->getResponseArr($this->expired);
             $this->responce_status = $this->expired;
        } else {
            $response = $this->getResponseArr($this->success);
            $response['token_id'] = $exist_token->id;
        }
         
        return $response;
    }
    
    /**
     * Decode hash contain [id | plainTextToken] and return real hash
     * @param string $req_token
     * @return string
     */
    public function decodeToken($req_token) {
        $string = base64_decode($req_token);
        $token_arr = explode('|', $string);        
        $token = $string;
        if(isset($token_arr[1])) {
            $token = hash('sha256', $token_arr[1]);
        }
        return $token;
    }
    
    /**
     * Return array with status and message
     * @param int $status
     * @param str $atrr
     * @return array ['success','message', 'fails']
     */
    public function getResponseArr($status, $atrr = null) {
        $response['success'] = $status == $this->success ? true : false;
        switch($status){
            case $this->not_found:
                $response['message'] = $atrr . ' not found';
                break;
            case $this->validation:
                $response['message'] = 'Validation failed' ;
                if($atrr == 'page') {              
                    $response['fails'][$atrr][] = 'The page must be at least 1.';                 
                } elseif($atrr == 'token') {
                    $response['fails'][$atrr][] = 'Token is required for this registration';  
                } else {
                    $response['fails'][$atrr][] = "The $atrr must be an integer.";
                }
                break;
            case $this->expired:
                $response['message'] = 'The token expired';
                break;
        }       
        
        return $response;
    }  
}
