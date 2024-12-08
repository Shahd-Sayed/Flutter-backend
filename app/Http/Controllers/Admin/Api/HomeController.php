<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Home\HomeResource;
use App\Models\Home;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Http\Resources\Admin\Home\HomeCollection;
use App\Http\Requests\Admin\Home\StoreHomeRequest;
use App\Http\Requests\Admin\Home\updateHomeRequest;
/**
 * @group Admin
 * Admin-related endpoints
 */

class HomeController extends Controller {
    /**
     * Display a listing of Home items.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function index() {
        if ( auth()->user()?->can( 'home.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $homes = Home::query()
        ->select( 'id', 'description', 'image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), HomeCollection::make( $homes ) );
    }
        /**
     * Display a listing of Trashed Home items.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function trashed() {
        if (!auth()->user()?->cant( 'home.trashed' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $homes = Home::query()
        ->select( 'id', 'description', 'image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->onlyTrashed()
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), HomeCollection::make( $homes ) );
    }
    /**
     * Store a new Home item.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function store( StoreHomeRequest $request ) {
        $home = Home::query()->create( $request->validated() );
        return ResponseHelpers::success( __( 'messages.created_successfully' ), HomeResource::make( $home ) );
    }
     /**
     * Display a specific Home item.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function show( Home $home ) {
        if ( auth()->user()?->can( 'home.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), HomeResource::make( $home ) );
    }
 
    /**
     * Update a specific item.
     *      @method PUT
     * @url /items/{id}
     * @group Admin
     * @subgroup Home
     */
    public function update( updateHomeRequest $request, Home $home ) {
        $home->update( $request->validated() );
        return ResponseHelpers::success( __( 'messages.updated_successfully' ), HomeResource::make( $home ) );
    }
          /**
     * soft-delete a specific item.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function destroy( Home $home ) {
        if ( auth()->user()?->can( 'home.delete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        if ( $home->delete() ):
        return ResponseHelpers::success( __( 'messages.deleted_successfully' ) );
        else:
        return ResponseHelpers::error( __( 'messages.deleting_error' ) );
        endif;
    }
         /**
     * restore a specific item from Trashed list.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function restore( $homeId ) {
        if ( auth()->user()?->can( 'home.restore' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        Home::query()->onlyTrashed()->where( 'id', $homeId )->select( 'id' )->firstOrFail()->restore();
        return ResponseHelpers::success( __( 'messages.restored_successfully' ) );
    }
          /**
     * force-delete a specific item.
     * 
     * @group Admin
     * @subgroup Home
     */
    public function forceDelete( Home $home ) {
        if ( auth()->user()?->can( 'home.forceDelete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $home->forceDelete();
        return ResponseHelpers::success( __( 'messages.force_deleted_successfully' ) );
    }
}
