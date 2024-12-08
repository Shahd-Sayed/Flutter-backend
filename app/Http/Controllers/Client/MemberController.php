<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\Member\MemberCollection;
use App\Http\Resources\Admin\Member\MemberResource;
use App\Models\Member;
use App\Helpers\Classes\ResponseHelpers;
/**
 * @group Client
 * Client-related endpoints
 */

class MemberController extends Controller {
        /**
     * Display a listing of Member items.
     * 
     * @group Client
     * @subgroup Member
     */
    public function index() {
        if ( auth()->user()?->can( 'member.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $members = Member::query()
        ->select( 'id', 'name', 'role', 'description', 'link1', 'link2', 'link3', 'image' )
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'asc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), MemberCollection::make( $members ) );
    }
    /**
     * Display a specific Member item by Id.
     * 
     * @group Client
     * @subgroup Member
     */
    public function show( Member $member ) {
        if ( auth()->user()?->can( 'member.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), MemberResource::make( $member ) );
    }
}
