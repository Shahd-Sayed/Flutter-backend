<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Committee\StoreCommitteeRequest;
use App\Http\Requests\Admin\Committee\UpdateCommitteeRequest;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Models\Committee;
use App\Http\Resources\Admin\Committee\CommitteeResource;
use App\Http\Resources\Admin\Committee\CommitteeCollection;
/**
 * @group Admin
 * Admin-related endpoints
 */

class CommitteController extends Controller {
    /**
     * Display a listing of Committee items.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function index() {
        if ( auth()->user()?->can( 'committee.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $committees = Committee::query()
        ->select( 'id', 'name', 'description' ,'image')
        ->with( [ 'courses', 'aboutUs' ] )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'asc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), CommitteeCollection::make( $committees ) );
    }
        /**
     * Display a listing of Trashed Committee items.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function trashed() {
        if ( !auth()->user()?->cant( 'committee.trashed' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $committees = Committee::query()
        ->select( 'id', 'name', 'description','image' )
        ->with( [ 'courses', 'aboutUs' ] )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->onlyTrashed()
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), CommitteeCollection::make( $committees ) );
    }
    /**
     * Store a new Committee item.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function store( StoreCommitteeRequest $request ) {
        
        $data = $request->validated();
        \Log::info($data); 

        try {
            $committee = Committee::create( $data );

            if (!empty($data['courses'])) {
                foreach ($data['courses'] as $course) {
                    if (isset($course['course_name'], $course['date'])) {
                        $committee->courses()->create($course);
                    } else {
                        return "error";
                    }
                }
            }
            

            if ( !empty( $data[ 'about_us' ] ) ) {
                $committee->aboutUs()->create( [
                    'description' => $data[ 'about_us' ],
                ] );
            }

            return ResponseHelpers::success(
                __( 'messages.created_successfully' ),
                CommitteeResource::make( $committee )
            );

        } catch ( \Exception $e ) {
            return ResponseHelpers::error(
                __( 'messages.creation_failed' ),
                $e->getMessage(),
                500
            );
        }
    }
    /**
     * Display a specific Committee item.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function show( Committee $committee ) {
        if ( auth()->user()?->can( abilities: 'committee.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), CommitteeResource::make( $committee ) );
    }
    /**
     * Update a specific item.
     * @method PUT
     * @url /items/{id}
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function update( UpdateCommitteeRequest $request, Committee $committee ) {
        try {
            $data = $request->validated();
            $committee->update( $data );

            if ( isset( $data[ 'courses' ] ) ) {
                $committee->courses()->delete();
                foreach ( $data[ 'courses' ] as $course ) {
                    $committee->courses()->create( $course );
                }
            }

            if ( isset( $data[ 'about_us' ] ) ) {
                $committee->aboutUs()->updateOrCreate( [], [ 'description' => $data[ 'about_us' ] ] );
            }

            return ResponseHelpers::success( __( 'messages.updated_successfully' ), CommitteeResource::make( $committee ) );
        } catch ( \Exception $e ) {
            return ResponseHelpers::error( $e->getMessage() );
        }
    }
    /**
     * soft-delete a specific item.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function destroy( Committee $committee ) {
        if ( auth()->user()?->can( 'committee.delete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        if ( $committee->delete() ):
        return ResponseHelpers::success( __( 'messages.deleted_successfully' ) );
        else:
        return ResponseHelpers::error( __( 'messages.deleting_error' ) );
        endif;
    }
            /**
     * restore a specific item from Trashed list.
     * 
     * @group Admin
     * @subgroup Committee
     */

    public function restore( $committeeId ) {
        if ( auth()->user()?->can( 'committee.restore' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        Committee::query()->onlyTrashed()->where( 'id', $committeeId )->select( 'id' )->firstOrFail()->restore();
        return ResponseHelpers::success( __( 'messages.restored_successfully' ) );
    }
        /**
     * force-delete a specific item.
     * 
     * @group Admin
     * @subgroup Committee
     */
    public function forceDelete( Committee $committee ) {
        if ( auth()->user()?->can( 'committee.forceDelete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $committee->forceDelete();
        return ResponseHelpers::success( __( 'messages.force_deleted_successfully' ) );
    }

}

