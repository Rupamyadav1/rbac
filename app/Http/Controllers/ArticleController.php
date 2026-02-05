<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ArticleController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

      public static function middleware():array
     {
        return [
            new Middleware('permission:create articles',only:['create']),
            new Middleware('permission:view articles',only:['index']),
            new Middleware('permission:edit articles',only:['edit']),
            new Middleware('permission:delete articles',only:['destroy']),


        ];

     }
    public function index()
    {
        $articles = Article::orderBy('id','DESC')->paginate(10);
        $data['articles']=$articles;
        return view('articles.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validator = Validator::make($request->all(),[
            'title'=>'required|min:3',
            'author'=>'required',
        ]);
        if($validator->passes()){
            $article = new Article();
            $article->title=$request->title;
            $article->text=$request->text;
            $article->author=$request->author;
            $article->save();
            return redirect()->route('admin.articles.index')->with('success','article added successfully');

        }else{
        return redirect()->route('articles.create')->withInput()->withErrors($validator);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $article=Article::find($id);
        $data['article']=$article;
        return view('articles.edit',$data);
    }

    
    public function update(Request $request, string $id)
    {
       
        $article=Article::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'author'=>'required',
        ]);
        if($validator->passes()){
            $article->title=$request->title;
            $article->text=$request->text;
            $article->author=$request->author;
            $article->save();
         return redirect()->route('articles.index')->with('success','Article Updated successfully');
           
        }else{
        return redirect()->route('article.edit',$id)->withInput()->withErrors($validator);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $article = Article::find($request->id);
        if($article == null){
            session()->flash('error','Article not found');
            return response()->json([
                'status'=>false,
            ]);
        }
        $article->delete();
                    session()->flash('success','Article deleted successfully');

        return response()->json([
            'status'=>true,
        ]);
        
    }
}
?>
