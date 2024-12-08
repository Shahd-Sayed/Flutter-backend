<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Models\Committee;
use App\Http\Resources\Admin\Committee\CommitteeResource;
use App\Http\Resources\Admin\Committee\CommitteeCollection;

/**
 * @group Client
 * Client-related endpoints
 */
class CommitteeController extends Controller
{
            /**
     * Display a listing of Committee items.
     * 
     * @group Client
     * @subgroup Committee
     */
 
    public function index() {
        if ( auth()->user()?->can( 'committee.index' ) ) {
            return ResponseHelpers::error( __( 'messages.not_allowed' ) );
        }
        $committees = Committee::query()
        ->select( 'id', 'name', 'description','image' )
        ->with(['courses', 'aboutUs'])
        ->orderBy( request( 'orderBy' ) ?: 'created_at', request( 'order' ) ?: 'desc' )
        ->paginate( request( 'perPage', 10 ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), CommitteeCollection::make( $committees ) );
    }
    /**
     * Display a specific Committee item by Id.
     * 
     * @group Client
     * @subgroup Committee
     */
    public function show( Committee $committee ) {
        if ( auth()->user()?->can( abilities: 'committee.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), CommitteeResource::make( $committee ) );
    }
}
