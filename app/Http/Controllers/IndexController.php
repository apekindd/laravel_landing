<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Service;
use App\People;
use App\Portfolio;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //
    public function execute(Request $request){
        $pages = Page::all();
        $portfolios = Portfolio::get(['name','filter','images']);
        $services = Service::where('id','<',20)->get();
        $peoples = People::take(3)->get();

        $tags = DB::table('portfolios')->distinct()->pluck('filter');

        $menu = [];
        foreach ($pages as $page){
            $item = ['title'=>$page->name,'alias'=>$page->alias];
            array_push($menu, $item);
        }
        $item=['title'=> 'Services','alias'=>'service'];
        array_push($menu, $item);
        $item=['title'=> 'Portfolio','alias'=>'Portfolio'];
        array_push($menu, $item);
        $item=['title'=> 'Team','alias'=>'team'];
        array_push($menu, $item);
        $item=['title'=> 'Contact','alias'=>'contact'];
        array_push($menu, $item);

        return view('site.index', [
            'menu'       => $menu,
            'pages'      => $pages,
            'portfolios' => $portfolios,
            'services'   => $services,
            'peoples'    => $peoples,
            'tags'    => $tags,
        ]);

    }
}
