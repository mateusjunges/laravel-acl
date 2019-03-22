<?php

namespace MateusJunges\ACL\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class UserPermissionController extends Controller
{
    protected $userModel;
    protected $permissionsModel;

    public function __construct()
    {
        $this->userModel = app(config('acl.models.user'));
        $this->permissionsModel = app(config('acl.models.permission'));
    }

    /**
     * Return view with all user permissions
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            if (Gate::allows('view-user-permissions')){
                $user = $this->userModel->find($id);
                $permissions = $user->permissions;
                return view('acl::users.permissions', compact(['user', 'permissions']));
            }else{
                return response( 'This action is unauthorized', Response::HTTP_UNAUTHORIZED);
            }
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $permissions = $this->permissionsModel->all();
            $users = $this->userModel->all();
            return view('acl::users.add-permission', compact(['permissions', 'users']));
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
            $user = $this->userModel->find($request->input('user'));
            $user->permissions()->syncWithoutDetaching($request->input('permissions'));
            $message = array(
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Permissões adicionadas com sucesso!',
            );
            session()->flash('message', $message);
            return response()->redirectToRoute('user.permissions.show', $user->id);
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal server error');
        }
    }

        /**
     * Remove a permission from a user
     * @param $user
     * @param $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($user, $permission)
    {
        try{
            if (Gate::denies('remove-user-permission'))
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'timer' => 4000,
                    'title' => 'Ops...',
                    'text' => 'Você não tem permissão para realizare esta ação no sistema!',
                ]);
            $user = $this->userModel->find($user);
            $user->permissions()->detach($permission);
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
                'text' => 'Ocorreu um erro em nosso servidor. Tente novamente mais tarde',
                'exception' => $exception,
            ]);
        }
    }


}