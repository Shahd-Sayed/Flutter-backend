<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\About\AboutResource;
use App\Models\Home;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Http\Resources\Admin\About\AboutCollection;
use App\Http\Requests\Admin\About\StoreAboutRequest;
use App\Http\Requests\Admin\About\UpdateAboutRequest;
use App\Models\About;
/**
 * @group Admin
 * Admin-related endpoints
 */

class AboutController extends Controller {

        /**
     * Display a listing of About items.
     * 
     * @group Admin
     * @subgroup About
     */
    public function index() {
        if ( auth()->user()?->can( 'about.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $abouts = About::query()
        ->select( 'id', 'name', 'description', 'image', 'video' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AboutCollection::make( $abouts ) );
    }
        /**
     * Display a listing of Trashed About items.
     * 
     * @group Admin
     * @subgroup About
     */
    public function trashed() {
        if ( !auth()->user()?->cant( 'about.trashed' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $abouts = About::query()
        ->select( 'id', 'name', 'description', 'image', 'video' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->onlyTrashed()
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AboutCollection::make( $abouts ) );
    }
    /**
     * Store a new About item.
     * 
     * @group Admin
     * @subgroup About
     */
    public function store( StoreAboutRequest $request ) {
        $about = About::query()->create( $request->validated() );
        return ResponseHelpers::success( __( 'messages.created_successfully' ), AboutResource::make( $about ) );
    }
    /**
     * Display a specific About item.
     * 
     * @group Admin
     * @subgroup About
     */
    public function show( About $about ) {
        if ( auth()->user()?->can( 'about.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AboutResource::make( $about ) );
    }
    /**
     * Update a specific item.
     * @method PUT
     * @url /items/{id}
     * @group Admin
     * @subgroup About
     */
    public function update( UpdateAboutRequest $request, About $about ) {
        $about->update( $request->validated() );
        return ResponseHelpers::success( __( 'messages.updated_successfully' ), AboutResource::make( $about ) );
    }
    /**
     * soft-delete a specific item.
     * 
     * @group Admin
     * @subgroup About
     */
    public function destroy( About $about ) {
        if ( auth()->user()?->can( 'about.delete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        if ( $about->delete() ):
        return ResponseHelpers::success( __( 'messages.deleted_successfully' ) );
        else:
        return ResponseHelpers::error( __( 'messages.deleting_error' ) );
        endif;
    }
        /**
     * restore a specific item from Trashed list.
     * 
     * @group Admin
     * @subgroup About
     */
    public function restore( $aboutId ) {
        if ( auth()->user()?->can( 'about.restore' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        About::query()->onlyTrashed()->where( 'id', $aboutId )->select( 'id' )->firstOrFail()->restore();
        return ResponseHelpers::success( __( 'messages.restored_successfully' ) );
    }

        /**
     * force-delete a specific item.
     * 
     * @group Admin
     * @subgroup About
     */

    public function forceDelete( About $about ) {
        if ( auth()->user()?->can( 'about.forceDelete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $about->forceDelete();
        return ResponseHelpers::success( __( 'messages.force_deleted_successfully' ) );
    }

}
