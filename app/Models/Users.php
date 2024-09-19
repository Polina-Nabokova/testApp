<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Intervention\Image\Facades\Image;


class Users extends Authenticatable {
    use HasApiTokens, HasFactory;
    
    // don't use default fields created_at, updatet_at 
    public $timestamps = false;
    
    protected $fillable = [
        'name', 
        'email',
        'phone', 
        'position_id',
        'photo'
    ];
    
    /**
     * Return users with pagination
     * @param int $count
     * @param int $page
     * @return array
     */
    public function getAll($count, $page) {
        return DB::table('users')
                ->select('users.id', 'users.name', 'users.email', 'users.phone',
                         'positions.name as position', 'users.position_id',
                         DB::raw('CONCAT(" '. asset('') .'", users.photo) as photo'))
                ->join('positions', 'users.position_id', '=', 'positions.id') 
                ->orderBy('users.id', 'asc')
                ->paginate(perPage:$count, page: $page);
    }
    
    /**
     * Upload, crop, resize and optimaze image
     * @param  object $image request file
     * @return string localPath
     */
    public function uploadImage($image){
        $photoName = time().'.'.$image->extension();        
        $img = Image::make($image);
        $img->fit(70, 70); // crop and resize to 70x70 pixel
        $img->save(public_path('images\tmp\/') . $photoName);
        $this->compressImage($photoName); // optimaze image
     
        return 'images/users/' . $photoName;
    }

    /**
     *  Optimazes image using tinypng.com API and move file to main folder
     * @param string $image name of image
     */
    public static function compressImage($image) {
        \Tinify\setKey(env('TINY_API_KEY'));
       
        $tmp_file = public_path('images\tmp\/') . $image;
        $users_file = public_path('images\users\/'). $image;
        $source = \Tinify\fromFile($tmp_file);
        $source->toFile($users_file);
        unlink($tmp_file);
    }  
    
    public static function getValidationRules() {
        return [
            'name'        => 'required|min:2|max:60',
            'email'       => 'required|email:rfc|unique:users,email',
            'phone'       => 'required|regex:/^[\+]{0,1}380([0-9]{9})$/|unique:users,phone',
            'position_id' => 'required',
            'photo'       => 'required|image|extensions:jpg,jpeg|max:5000',
            'page'        => 'integer|min:1',
            'count'       => 'integer|min:1'            
        ];
    }
    
    
   
}
