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
    protected static $tiny_api_key = "ThXWgv1kBLpN3jhdzDnrF6kfX7dwBms7";


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
    public static function uploadImage($image, $is_string_data = false){
        $tmp_folder = public_path('images/tmp');
        if(!file_exists($tmp_folder)) {
            mkdir("images/tmp", 0755);
            mkdir("images/users", 0755);
        }
        if($is_string_data) {            
            file_put_contents($tmp_folder.'/tmp.jpg', $image); 
            $image = $tmp_folder.'/tmp.jpg';
        }
        $photoName = time().'.jpg';       
        $img = Image::make($image);
        $img->fit(70, 70); // crop and resize to 70x70 pixel
        if($img->extension != 'jpg' || $img->extension != 'jpeg'){
          $img->encode('jpg');  // for auto generated users
        }
        $img->save($tmp_folder .'/'. $photoName);
        $is_image = self::compressImage($photoName); // optimaze image
        if(!$is_image) return '';
        return 'images/users/' . $photoName;
    }

    /**
     *  Optimazes image using tinypng.com API and move file to main folder
     * @param string $image name of image
     */
    public static function compressImage($image) {
        \Tinify\setKey(self::$tiny_api_key);
       
        $tmp_file = public_path('images/tmp/') . $image;
        $users_file = public_path('images/users/'). $image;
        if (!is_file($tmp_file)) return false;
        $source = \Tinify\fromFile($tmp_file);
        $source->toFile($users_file);
        unlink($tmp_file);
        @unlink(public_path('images/tmp/').'tmp.jpg');
        return true;
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
