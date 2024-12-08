<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Member\StoreMemberRequest;
use App\Http\Requests\Admin\Member\UpdateMemberRequest;
use App\Http\Resources\Admin\Member\MemberCollection;
use App\Http\Resources\Admin\Member\MemberResource;
use App\Models\Member;
use App\Helpers\Classes\FileHelpers;
use App\Helpers\Classes\ResponseHelpers;
/**
 * @group Admin
 * Admin-related endpoints
 */
class MemberController extends Controller
{
    /**
     * Display a listing of Member items.
     * 
     * @group Admin
     * @subgroup Member
     */
    public function index() {
        if ( auth()->user()?->can( 'member.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $members = Member::query()
        ->select( 'id', 'name','role','description','link1','link2', 'link3', 'image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), MemberCollection::make( $members ) );
    }
        /**
     * Display a listing of Trashed Member items.
     * 
     * @group Admin
     * @subgroup Member
     */
    public function trashed() {
        if (!auth()->user()?->cant( 'member.trashed' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $members = Member::query()
        ->select( 'id', 'name','role','description','link1','link2', 'link3', 'image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->onlyTrashed()
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), MemberCollection::make( $members ) );
    }

    /**
     * Store a new Member item.
     * 
     * @group Admin
     * @subgroup Member
     */
    public function store( StoreMemberRequest $request ) {
        $member = Member::query()->create( $request->validated() );
        return ResponseHelpers::success( __( 'messages.created_successfully' ), MemberResource::make( $member ) );
    }

        /**
     * Display a specific Member item.
     * 
     * @group Admin
     * @subgroup Member
     */

    public function show( Member $member ) {
        if ( auth()->user()?->can( 'member.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), MemberResource::make( $member ) );
    }
     /**
     * Update a specific item.
     *      @method PUT
     * @url /items/{id}
     * @group Admin
     * @subgroup Member
     */
    public function update( UpdateMemberRequest $request, Member $member ) {
        $member->update( $request->validated() );
        return ResponseHelpers::success( __( 'messages.updated_successfully' ), MemberResource::make( $member ) );
    }
      /**
     * soft-delete a specific item.
     * 
     * @group Admin
     * @subgroup Member
     */
    public function destroy( Member $member ) {
        if ( auth()->user()?->can( 'member.delete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        if ( $member->delete() ):
        return ResponseHelpers::success( __( 'messages.deleted_successfully' ) );
        else:
        return ResponseHelpers::error( __( 'messages.deleting_error' ) );
        endif;
    }
        /**
     * restore a specific item from Trashed list.
     * 
     * @group Admin
     * @subgroup Member
     */
      public function restore( $memberId ) {
        if ( auth()->user()?->can( 'member.restore' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        Member::query()->onlyTrashed()->where( 'id', $memberId )->select( 'id' )->firstOrFail()->restore();
        return ResponseHelpers::success( __( 'messages.restored_successfully' ) );
    }
         /**
     * force-delete a specific item.
     * 
     * @group Admin
     * @subgroup Member
     */
    public function forceDelete( Member $member ) {
        if ( auth()->user()?->can( 'member.forceDelete' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        $member->forceDelete();
        return ResponseHelpers::success( __( 'messages.force_deleted_successfully' ) );
    }

}
