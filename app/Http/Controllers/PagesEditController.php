<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagesEditController extends Controller
{
    //
    public function execute(Page $page, Request $request){
        /*$page = Page::find($page);*/

        if($request->isMethod('delete')){
            $page->delete();
            return redirect()->route('admin')->with('status','Страница удалена');
        }

        if($request->isMethod('post')){
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                'name'  => 'required|max:255',
                'alias' => 'required|max:255|unique:pages,alias,'.$input['id'],
                'text' => 'required',
            ]);

            if($validator->fails()){
                return redirect()
                    ->route('pagesEdit',['page'=>$input['id']])
                    ->withErrors($validator);
            }

            if($request->hasFile('images')){
                $file = $request->file('images');
                $input['images'] = $file->getClientOriginalName();
                $file->move(public_path().'/assets/img',$input['images']);
            }else{
                $input['images'] = $input['old_images'];
            }

            unset($input['old_images']);

            $page->fill($input);

            if($page->update()){
                return redirect()->route('admin')->with('status','Страница обновлена');
            }
        }

        $old = $page->toArray();
        if(view()->exists('admin.pages_edit')){
            $data = [
                'title' => 'Редактирование страницы - '.$old['name'],
                'data'  => $old,
            ];
            
            return view('admin.pages_edit', $data);
        }
        abort('404','View admin.pages_edit not exetst');
    }
}
