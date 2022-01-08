<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Exceptions\InputInvalid;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ArticleController extends Controller
{
    private function getArticle( $id ){
        $article = Article::find($id);
        
        if( is_null($article) ){
            throw new NotFoundHttpException("article not found");
        }

        return $article;
    }

    private function validateInput( $input, $rules ){
        $validation = Validator::make( $input, $rules );

        if( $validation->fails() ){
            throw new InputInvalid( $validation->errors() );
        }

        return true;
    }

    public function list( Request $request ){
        $articles = Article::all();
        
        return [
            'status' => 200,
            'message' => NULL,
            'data' => ArticleResource::collection($articles)
        ];
    }

    public function retrieve( Request $request, $id = NULL ){
        return [
            'status' => 200,
            'message' => NULL,
            'data' => new ArticleResource($this->getArticle($id))
        ];
    }

    public function delete( Request $request, $id = NULL ){
        $article = $this->getArticle($id);

        if( Storage::disk('public')->exists($article->image) ){
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return [
            'status' => 200,
            'message' => 'Article has been successfully deleted',
            'data' => []
        ];
    }

    public function create( Request $request ){
        $this->validateInput($request->all(), [
            'title'     => 'required|string|max:150',
            'content'   => 'required|string'
        ]);

        $article = new Article;
        $article->title = $request->title;
        $article->content = $request->content;

        if( !is_null($request->file('image')) ){
            if( $request->file('image')->isValid() ){
                $file = $request->file('image');
                $file->store('images', 'public');
                $article->image = 'images/'. $file->hashName();
            }
        }else{
            $article->image = NULL;
        }

        $article->save();

        return [
            'status'    => 201,
            'message'   => 'Article has been successfully created',
            'data'      => new ArticleResource($article)
        ];
    }

    public function update( Request $request, $id = NULL ){
        $article = $this->getArticle($id);

        $article->title = $request->input('title', $article->title);
        $article->content = $request->input('content', $article->content);

        if( !is_null($request->file('image')) ){
            if( $request->file('image')->isValid() ){
                //delete existed file
                if( Storage::disk('public')->exists($article->image) ){
                    Storage::disk('public')->delete($article->image);
                }

                //upload new file
                $file = $request->file('image');
                $file->store('images', 'public');
                $article->image = 'images/'.$file->hashname();
            }
        }else{
            $article->image = NULL;
        }

        $article->save();

        return [
            'status'    => 200,
            'message'   => 'Article has been successfully updated',
            'data'      => new ArticleResource($article)
        ];
    }
}
