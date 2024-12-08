<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\Home\HomeResource;
use App\Models\Home;
use App\Helpers\Classes\ResponseHelpers;
use App\Http\Resources\Admin\Home\HomeCollection;

/**
 * @group Client
 * Client-related endpoints
 */
class HomeController extends Controller
{
         /**
     * Display a listing of Home items.
     * 
     * @group Client
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
     * Display a specific Home item by Id.
     * 
     * @group Client
     * @subgroup Home
     */
     public function show( Home $home ) {
        if ( auth()->user()?->can( 'home.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), HomeResource::make( $home ) );
    }
}
