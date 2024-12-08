<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Models\Achievement;
use App\Http\Resources\Admin\Achievement\AchievementCollection;
use App\Http\Resources\Admin\Achievement\AchievementResource;
use App\Http\Requests\Admin\Achievement\StoreAchievementRequest;
use App\Http\Requests\Admin\Achievement\UpdateAchievementRequest;

/**
 * @group Admin
 * Admin-related endpoints
 */
class AchievementController extends Controller
{
     /**
     * Display a listing of Achievement items.
     * 
     * @group Admin
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
     * Display a listing of Trashed Achievement items.
     * 
     * @group Admin
     * @subgroup Achievement
     */
    public function trashed() {
        if (!auth()->user()?->cant( 'achievement.trashed' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $achievements = Achievement::query()
        ->select( 'id', 'name', 'date' , 'location' ,'members' , 'rank','image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->onlyTrashed()
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AchievementCollection::make( $achievements ) );
    }
    /**
     * Store a new Achievement item.
     * 
     * @group Admin
     * @subgroup Achievement
     */

    public function store( StoreAchievementRequest $request ) {
        $achievement = Achievement::query()->create( $request->validated() );
        return ResponseHelpers::success( __( 'messages.created_successfully' ), AchievementResource::make( $achievement ) );
    }
    /**
     * Display a specific Achievement item.
     * 
     * @group Admin
     * @subgroup Achievement
     */
    public function show( Achievement $achievement ) {
        if ( auth()->user()?->can( 'achievement.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AchievementResource::make( $achievement ) );
    }
    /**
     * Update a specific item.
     * @method PUT
     * @url /items/{id}
     * @group Admin
     * @subgroup Achievement
     */
    public function update( UpdateAchievementRequest $request, Achievement $achievement ) {
        $achievement->update( $request->validated() );
        return ResponseHelpers::success( __( 'messages.updated_successfully' ), AchievementResource::make( $achievement ) );
    }
     /**
     * soft-delete a specific item.
     * 
     * @group Admin
     * @subgroup Achievement
     */
    public function destroy( Achievement $achievement ) {
        if ( auth()->user()?->can( 'achievement.delete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        if ( $achievement->delete() ):
        return ResponseHelpers::success( __( 'messages.deleted_successfully' ) );
        else:
        return ResponseHelpers::error( __( 'messages.deleting_error' ) );
        endif;
    }
          /**
     * restore a specific item from Trashed list.
     * 
     * @group Admin
     * @subgroup Achievement
     */
    public function restore( $achievementId ) {
        if ( auth()->user()?->can( 'achievement.restore' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        Achievement::query()->onlyTrashed()->where( 'id', $achievementId )->select( 'id' )->firstOrFail()->restore();
        return ResponseHelpers::success( __( 'messages.restored_successfully' ) );
    }
            /**
     * force-delete a specific item.
     * 
     * @group Admin
     * @subgroup Achievement
     */
     public function forceDelete( Achievement $achievement ) {
        if ( auth()->user()?->can( 'achievement.forceDelete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $achievement->forceDelete();
        return ResponseHelpers::success( __( 'messages.force_deleted_successfully' ) );
    }

}
