<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\SubCategory;

class BlogController extends Controller
{
    public function categoryPosts(Request $request, $slug)
    {

        if( !$slug ){
            return abort(404);
        } else {

            $subcategory = SubCategory::where('slug', $slug)->first();
            
            if( !$subcategory ){
                return abort(404);
            } else{
                $posts = Post::where('subcategory_id', $subcategory->id)->orderBy('created_at','desc')->paginate(6);

                $data = [
                    'pageTitle' => 'Category - '.$subcategory->subcategory_name,
                    'category' => $subcategory,
                    'posts' => $posts
                ];
                
                return view('front.pages.category_posts', $data);

            }
        }
    }
}
