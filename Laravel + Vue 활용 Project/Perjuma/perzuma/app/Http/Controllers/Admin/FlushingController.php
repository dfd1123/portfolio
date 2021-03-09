
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Flushing;



class FlushingController extends Controller
{

    public __constructor(){
        return '1234';
    }

    public function index(Request $request, $page='login')
    {
        $page = strtolower($page);

        $query = null;
        switch ($page) {
            case 'tradelist':
            $this ->flushing($request);
            break;

            case 'garbage':
            break;
            default:
            break;
        }

        $returnView = view($view);
        $returnView->query = $query;
        return  $returnView;
    }
}