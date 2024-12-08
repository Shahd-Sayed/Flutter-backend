<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Models\Achievement;
use App\Http\Resources\Admin\Achievement\AchievementCollection;
use App\Http\Resources\Admin\Achievement\AchievementResource;
/**
 * @group Client
 * Client-related endpoints
 */
class AchievementController extends Controller
{        /**
    * Display a listing of Achievement items.
    * 
    * @group Client
    * @subgroup Achievement
    */
      public function index() {
        if ( auth()->user()?->can( 'achievement.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $achievement = Achievement::query()
        ->select( 'id', 'name', 'date' , 'location' ,'members' , 'rank','image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AchievementCollection::make( $achievement ) );
    }

         /**
     * Display a specific Achievement item by Id.
     * 
     * @group Client
     * @subgroup Achievement
     */
        public function show( Achievement $achievement ) {
        if ( auth()->user()?->can( 'achievement.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AchievementResource::make( $achievement ) );
    }
}
