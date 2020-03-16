<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleStar;
use App\Operate;
use App\Selection;
use App\Tag;
use App\Timeline;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * 根据id获取文章信息
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticleById(Request $request, $id = 1)
    {
        // 获取访问者的ip
        $visitorIp = $request->getClientIp();

        $article = Article::where('id', $id)->with(['user'=>function($query) {
            $query->select('id', 'username');
        }, 'star'=>function($query) use($visitorIp) {
            $query->select('articleId', 'state')->where('visitorIp', $visitorIp);
        }, 'tags'=>function($query) {
            $query->select('tagId as value', 'label');
        }])->first()->makeHidden(['id', 'userId', 'updated_at']);

        // 隐藏不使用的属性
        isset($article->user) ? $article->user->makeHidden('id') : null;
        isset($article->star) ? $article->star->makeHidden('articleId') : null;
        isset($article->tags) ? $article->tags->makeHidden('pivot') : null;

        // 尝试浏览数自增1，若失败也没关系，此属性不是特别重要
        try{
            $article->viewNum++;
            $article->save();
        } catch(\Exception $e) {
            echo $e;
        }

        return response()->json([
            'state' => 200,
            'data' => $article
        ]);
    }

    /**
     * 文章分页数据
     * @param int $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticleListByPage($page = 1, $limit = 10)
    {
        $articleModel = Article::where('userId', 1);
        $total = $articleModel->get()->count('id');
        $articles = $articleModel->with(['tags' => function($query) {
            $query->select('tagId as value', 'label');
        }, 'user'])->orderBy('created_at', 'DESC')->skip(($page - 1) * $limit)->take($limit)->get()->makeHidden(['userId', 'updated_at']);

        $articles->map(function($article) {
            $article->isAddUse;
            $article->jumpLink;
            return $article->tags->makeHidden('pivot');
        });

        return response()->json([
            'state' => 200,
            'total' => $total,
            'data' => $articles
        ]);
    }

    /**
     * 获取精选数据
     * @param int $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelection($type = null, $limit = 5) {
        if (!$type) {
            return response()->json([
                'state' => 300,
                'msg' => 'type is not find or undefined',
                'data' => null
            ]);
        }
        $selections = Selection::setModelName(Selection::$model[$type])->where('type', $type)->with(['origin' => function($query) {
            $query->select('id', 'title', 'useNum')->with('tags');
        }])->limit($limit)->orderBy('created_at', 'desc')->get();

        $selections->map(function($selection) {
            return $selection->origin->tags->makeHidden('pivot');
        });

        return response()->json([
            'state' => 200,
            'data' => $selections
        ]);
    }

    /**
     * 设置打星状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStarState(Request $request)
    {
        $data['state'] = !$request->get('state');
        $data['articleId'] = $request->get('articleId');
        $data['visitorIp'] = $request->getClientIp();
        $articleStar = ArticleStar::where(['visitorIp'=>$data['visitorIp'], 'articleId'=>$data['articleId']])->first();
        if (is_null($articleStar)) {
            if (ArticleStar::create($data)) {
                $res = true;
            } else {
                $res = false;
            }
        } else {
            $articleStar['state'] = $data['state'];
            $res = $articleStar->save();
        }

        return response()->json([
            'errno' => $res ? 'success' : 'error',
            'state' => $data['state']
        ]);
    }

    /**
     * 设置操作状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOperate(Request $request) {
        $data['userId'] = \Auth::id() ?? str_replace('.', '', $request->getClientIp());
        $data['originId'] = $request->get('originId');
        $data['type'] = Operate::$type[$request->get('type')];
        $data['state'] = $request->get('state');
        $Operate = Operate::where($data)->first();

        \DB::beginTransaction();
        try{
            $data['state'] = !$data['state'];
            if (is_null($Operate)) {
                if (Operate::create($data)){
                    $res = true;
                } else {
                    $res = false;
                }
            } else {
                $Operate['state'] = $data['state'];
                $Operate->save();
            }
            $Article = Article::find($data['originId']);
            $data['state'] ? $Article->useNum++ : $Article->useNum--;
            $Article->save();
            $res = true;
            \DB::commit();
        }catch (\Exception $e) {
            $res = false;
            \DB::rollBack();
        }



        return response()->json([
            'errno' => $res ? 'success' : 'error',
            'state' => $data['state']
        ]);
    }

    /**
     * 创建或保存文章
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createArticle(Request $request)
    {
        $userId = \Auth::id();
        $articleId = $request->get('id');
        $article = [
            'userId' => $userId || 1,
            'title' => $request->get('title'),
            'content' => htmlspecialchars($request->get('content')),
            'desc' => str_limit(strip_tags($request->get('content')), 200)
        ];
        $tags = collect($request->get('tags'))->map(function($tag, $key) {
            return ['tagId' => $tag];
        });
        \DB::beginTransaction();
        try {
            if (is_null($articleId)) {
                $article = Article::create($article);
                $article->tags()->attach($tags);
            } else {
                $res = $article;
                $article = Article::find($articleId);
                $article->update($res);
                $article->tags()->sync($tags);
            }
            $timeline = [
                'userId' => $userId || 1,
                'content' => ($articleId ? '修改' : '创建').'了一条"'.$article['title'].'"学习记录！'
            ];
            Timeline::create($timeline);
            \DB::commit();
            $state = 200;
        } catch (\Exception $e) {
            \DB::rollBack();
            $state = 100;
        }

        return response()->json([
            'state' => $state,
            'data' => $state === 200 ? $article : []
        ]);
    }

    /**
     * 获取标签
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTags()
    {
        $tags = Tag::where('id', '>', 0)->select('id as value', 'label')->get();

        return response()->json([
            'state' => is_null($tags) ? 100 : 200,
            'data' => $tags
        ]);
    }

    /**
     * 获取时间线分页数据
     * @param int $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimelinesByPage($page = 1)
    {
        $timelinesModel = Timeline::where('userId', 1);
        $total = $timelinesModel->get()->count('id');
        $timelines = $timelinesModel->orderBy('created_at', 'desc')->skip(($page - 1) * 10)->take(10)->get()->makeHidden(['userId', 'updated_at']);

        return response()->json([
            'state' => 200,
            'total' => $total,
            'data' => $timelines
        ]);
    }

    /**
     * 验证是否登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate()
    {
        return response()->json([
            'state' => is_null(\Auth::id()) ? 100 : 200
        ]);
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (\Auth::attempt([
            'username' => $request->get('username'),
            'password' => $request->get('password')
        ])){
            $res = [
                'state' => 200,
                'message' => '登录成功'
            ];
        }else {
            $res = [
                'state' => 100,
                'message' => '账户或密码错误'
            ];
        }

        return response()->json($res);
    }

    /**
     * 登出
     */
    public function logout()
    {
        \Auth::logout();
    }
}
