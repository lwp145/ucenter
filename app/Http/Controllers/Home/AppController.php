<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use App\Model\App;
use App\Model\UserRole;
use App\Services\Api;

class AppController extends Controller
{
    public function index()
    {
        return view('home.app.index');
    }

    public function lists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'home_url');
        $searchFields = array('name', 'title');

        $apps = Session::get('apps');
        $roles = Session::get('roles');
        foreach ($apps as $v) {
            $appIdsArray[] = $v['id'];
        }

        $data = App::whereIn('id', $appIdsArray)
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields)
            ->toArray();
        foreach ($data as &$value) {
            foreach ($roles[$value['id']] as $v) {
                $value['roles'][] = array_merge($v, UserRole::where('app_id', $value['id'])
                    ->where('user_id', Auth::id())
                    ->where('role_id', $v['id'])
                    ->first(array('created_at'))
                    ->toArray());
            }
        }
        $draw = (int)$request->draw;
        $recordsTotal = App::whereIn('id', $appIdsArray)->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 从当前应用中移出用户
    public function remove(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->ids;
            $appIdsArray = UserRole::where('user_id', Auth::id())->whereIn('role_id', $ids)->lists('app_id')->toArray();
            $userIdsArray = App::whereIn('id', $appIdsArray)->lists('user_id')->toArray();
            if (in_array(Auth::id(), $userIdsArray)) {
                // return Api::jsonReturn(0, '移除失败, 不允许移除创建者');
            }
            $result = UserRole::where('user_id', Auth::id())->whereIn('role_id', $ids)->delete();

            DB::commit();
            return Api::jsonReturn(1, '移除成功', array('deleted_num' => $result));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
            return Api::jsonReturn(0, '移除失败', array('deleted_num' => 0));
        }
    }
}
