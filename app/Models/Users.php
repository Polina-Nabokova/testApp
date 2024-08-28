<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model {
    use HasApiTokens, HasFactory;
    // don't use default fields created_at, updatet_at 
    public $timestamps = false;
    
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
                ->orderBy('users.id', 'asc')->paginate(perPage:$count, page: $page);
    }
    
    
    
   
}
