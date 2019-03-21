<?php

namespace MateusJunges\ACL\Http\Controllers\Groups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MateusJunges\ACL\Http\Models\Group;
use MateusJunges\ACL\Http\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        try{
            $groups = Group::all();
            return view('acl::groups.index', compact('groups'));
//        }catch (\Exception $exception){
//            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
//        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $permissions = Permission::all();
            return view('acl::groups.create', compact('permissions'));
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        try{
            $group = new Group();
            $group->fill($request->all());
            $group->save();
            $group->permissions()->attach($request->input('permissions'));
            $message = array(
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Grupo criado com sucesso!',
            );
            session()->flash('message', $message);
            return response()->redirectToRoute('groups.index')->with($message);
//        }catch (\Exception $exception){
//            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $group = Group::find($id);
            $permissions = $group->permissions;
            return view('acl::groups.permissions', compact([
                'group',
                'permissions'
            ]));
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $group = Group::find($id);
            $permissions = Permission::all();
            return view('acl::groups.edit', compact(['group', 'permissions']));
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $group = Group::find($id);
            $group->delete();
            return response()->json([
               'code' => Response::HTTP_OK,
               'timer' => 4000,
               'title' => 'Sucesso!',
               'text' => 'Grupo removido com sucesso!',
               'icon' => 'success',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'timer' => 4000,
                'icon' => 'error',
                'text' => 'Ocorreu um erro. Tente novamente mais tarde!',
            ]);
        }
    }
}
