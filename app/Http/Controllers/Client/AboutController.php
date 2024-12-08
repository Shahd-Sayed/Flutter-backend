<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Classes\ResponseHelpers;
use App\Models\About;
use App\Http\Resources\Admin\About\AboutCollection;
use App\Http\Resources\Admin\About\AboutResource;

/**
 * @group Client
 * Client-related endpoints
 */

class AboutController extends Controller {
        /**
     * Display a listing of About items.
     * 
     * @group Client
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
     * Display a specific About item by Id.
     * 
     * @group Client
     * @subgroup About
     */
    public function show( About $about ) {
        if ( auth()->user()?->can( 'about.show' ) ) return ResponseHelpers::error( __( 'messages.not_allowed' ) );

        return ResponseHelpers::success( __( 'messages.retrieved_successfully' ), AboutResource::make( $about ) );
    }
}
