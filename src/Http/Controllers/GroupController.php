<?php

namespace MateusJunges\ACL\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    protected $groupModel;
    protected $groupHasPermissionModel;
    protected $permissionModel;


    /**
     * GroupController constructor.
     */
    public function __construct()
    {
        $groupModelClass = config('acl.models.group');
        $groupHasPermissionClass = config('acl.models.GroupHasPermission');
        $permissionClass = config('acl.models.permission');
        $this->groupModel = app($groupModelClass);
        $this->groupHasPermissionModel = app($groupHasPermissionClass);
        $this->permissionModel = app($permissionClass);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if(Gate::denies('groups.view'))
                return abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
            $groups = $this->groupModel->all();
            return view('acl::groups.index', [
                'groups' => $groups,
            ]);
        }catch (\Exception $exception) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if(Gate::denies('groups.create'))
                return abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
            $permissions = $this->permissionModel->all();
            return view('acl::groups.create',[
                'permissions' => $permissions,
            ]);
        }catch (\Exception $exception){

        }
    }

    public function edit($id)
    {
        try{
            $group = $this->groupModel->find($id);
            $permisions = $this->permissionModel->all();
            return view('acl::groups.edit', [
                'group' => $group,
                'permissions' => $permisions,
            ]);
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal server error');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if(Gate::denies('groups.create'))
                return abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
            $group = new $this->groupModel;
            $group->fill($request->all());
            $group->save();

            foreach ($request->permissions as $permission) {
                $group->permissions()->atach($permission);
            }

            $message = array(
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Grupo criado com sucesso!',
            );
            session()->flash('message', $message);
            return redirect()->route('groups.index');
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal server error');
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $group = $this->groupModel->find($id);
            $group->update($request->except('permissions'));

            $group->permissions()->sync($request->input('permissions'));

            $message = array(
                'title' => 'Sucesso!',
                'type' => 'success',
                'text' => 'Grupo de permissões atualizado com sucesso!',
            );
            session()->flash('message', $message);
            return redirect()->route('groups.index');
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server error');
        }
    }

    public function permissions($id){
        try{
            if(Gate::denies('groups.viewPermissions')){
                $message = array(
                    'title' => 'Acesso negado!',
                    'text'  => 'Você não tem permissão para acessar esta área do sistema.',
                    'type'  => 'danger'
                );
                session()->flash('message', $message);
                return redirect()->back();
            }
            $group = $this->groupModel->find($id);
            $permissions = $group->permissions;
            foreach ($permissions as $permission) {
                $permission->relation_id = $this->groupHasPermissionModel->where([
                    ['group_id', '=', $group->id],
                    ['permission_id', '=', $permission->id]
                ])->first()->id;
            }
            return view('acl::groups.permissions', [
                'permissions' => $permissions,
                'group' => $group
            ]);
        }catch (\Exception $exception){
            return response('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeGroupPermission($id){
        try{
            if(Gate::denies('groups.removeGroupPermission'))
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'timer' => 4000,
                    'title' => 'Acesso negado!',
                    'icon' => 'warning',
                    'text' => 'Você não tem permissão para realizar esta ação no sistema!',
                ]);
            $permission = $this->groupHasPermissionModel->find($id);
            $permission->delete();
            return response()->json([
                'code' => Response::HTTP_OK,
                'timer' => 4000,
                'title' => 'Sucesso!',
                'icon' => 'success',
                'text' => 'Permissão removida com sucesso!',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'timer' => 4000,
                'title' => 'Ops...',
                'icon' => 'error',
                'text' => 'Ocorreu um erro. Tente novamente mais tarde',
            ]);
        }
    }



    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            if(Gate::denies('groups.delete'))
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'title' => 'Ops...',
                    'text' => 'Permissão negada!',
                    'icon' => 'warning',
                    'timer' => 4000,
                ]);
            $group = $this->groupModel->find($id);
            $group->delete();
            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Sucesso!',
                'text' => 'Grupo removido com sucesso!',
                'icon' => 'success',
                'timer' => 4000,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'title' => 'Ops...',
                'text' => 'Ocorreu um erro. Tente novamente mais tarde.',
                'icon' => 'error',
                'timer' => 4000,
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trashed()
    {
        try{
            $groups = $this->groupModel->onlyTrashed()->get();
            return view('acl::groups.trashed', [
                'groups' => $groups,
            ]);
        }catch (\Exception $exception){

        }
    }

    public function restore($id)
    {
        try{
            $group = $this->groupModel->withTrashed()->find($id);
            $group->restore();
            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Sucesso!',
                'text' => 'Grupo restaurado com sucesso!',
                'icon' => 'success',
                'timer' => 4000,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'title' => 'Acesso negado!',
                'text' => 'Você não tem permissão para realizar esta ação no sistema!',
                'icon' => 'warning',
                'timer' => 4000,
            ]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function permanentlyDelete($id)
    {
        try{
            $group = $this->groupModel->withTrashed()->find($id);
            $group->forceDelete();
            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Sucesso!',
                'text' => 'Grupo removido permanentemente!',
                'icon' => 'success',
                'timer' => 4000,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'title' => 'Acesso negado!',
                'text' => 'Você não tem permissão para realizar esta ação no sistema!',
                'icon' => 'warning',
                'timer' => 4000,
            ]);
        }
    }
}
