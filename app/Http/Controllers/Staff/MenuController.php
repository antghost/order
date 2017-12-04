<?php

namespace App\Http\Controllers\Staff;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('staff.menu.index', ['menus' => Menu::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required|max:100',
            'type' => 'required|max:1',
        ]);

        $name = $request->input('name');
        $type = $request->input('type');
        $active = $request->input('active');

        $menu = Menu::create([
            'name' => $name,
            'type' => $type,
            'active' => $active,
        ]);

        return response()->json(['添加完成']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->name = $request->input('name');
        $menu->type = $request->input('type');
        $menu->save();
        return response()->json(['添加成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::destroy($id);
        return response()->json(['删除成功']);
    }

    public function s(Request $request)
    {
        $request->flash();
        $name = $request->input('name'); //名称
        $check = $request->except('name'); //选择项
        //传入请求中存在指定的输入值的时候才执行查询
        $menus = Menu::when($name, function ($query) use ($name){
            return $query->where('name', 'like', '%'.$name.'%');
        })
            ->when(isset($check['breakfast']), function ($query){
                return $query->orWhere('type', '=', 1);
            })
            ->when(isset($check['lunch']), function ($query){
                return $query->orWhere('type', '=', 2);
            })
            ->when(isset($check['dinner']), function ($query){
                return $query->orWhere('type', '=', 3);
            })
            ->when(isset($check['hightea']), function ($query){
                return $query->orWhere('type', '=', 4);
            })
            ->when(isset($check['today_menu']), function ($query){
                return $query->orWhere('active', '=', 1);
            })
            ->paginate(10);
        //附加查询参数到分页链接中
        if (!is_null('name')) $menus = $menus->appends(['name' => $name]);
        if (isset($check['breakfast'])) $menus = $menus->appends(['breakfast' => isset($check['breakfast'])]);
        if (isset($check['lunch'])) $menus = $menus->appends(['breakfast' => isset($check['lunch'])]);
        if (isset($check['dinner'])) $menus = $menus->appends(['dinner' => isset($check['dinner'])]);
        if (isset($check['hightea'])) $menus = $menus->appends(['hightea' => isset($check['hightea'])]);
        if (isset($check['today_menu'])) $menus = $menus->appends(['today_menu' => isset($check['today_menu'])]);

        return view('staff.menu.index',['menus' => $menus]);
    }

    public function todayMenu($id)
    {
        $data = [];
        $menu = Menu::findOrFail($id);
//        $menu->active ? $menu->active = 0 : $menu->active = 1;
        if ($menu->active) {
            $menu->active = 0 ;
            $data['name'] = '添加';
        } else {
            $menu->active = 1 ;
            $data['name'] = '取消';
        }
        $menu->save();
        return response()->json($data);
    }

    public function massTodayMenu(Request $request)
    {
        $type = $request->input('type');
        $ids = $request->input('ids');
        $menu = Menu::update('active','=', 1)->whereIn('id',$ids);
        return redirect()->back();
    }
}
