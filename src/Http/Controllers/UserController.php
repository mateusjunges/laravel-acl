<?php

namespace MateusJunges\ACL\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use MateusJunges\ACL\Http\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Return the view that shows all registered users
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('acl::layouts.page');
        try{
            if(Gate::denies('users.view')){
                $message = array(
                    'type' => 'danger',
                    'title' => 'Acesso negado!',
                    'text' => 'Você não tem permissão para realizar esta ação no sistema!',
                );
                session()->flash('message', $message);
                return redirect()->back();
            }
            return view('users.index');
        }catch (\Exception $exception){
            return response('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Users table columns
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function columns(){
        $columns = array();
        $iterator = -1;
        $columns += array(++$iterator => 'Nome', ++$iterator => 'Username');
        if (Gate::allows('users.viewPermissions'))
            $columns += array( ++$iterator => 'Listar Permissões');
        if (Gate::allows('deniedPermissions.view'))
            $columns += array(++$iterator => 'Permissões bloqueadas');
        if (Gate::allows('users.update'))
            $columns += array(++$iterator => 'Editar');
        if (Gate::allows('users.delete'))
            $columns += array(++$iterator => 'Remover');
        if (Gate::allows('roles.view'))
            $columns += array(++$iterator => 'Ver Grupos');
        $columns += array(++$iterator => 'Email');
        return response(collect($columns)->map(function ($item){
            return ['data' => $item];
        })->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Users table ajax data
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function data(Request $request){
        try{
            $iterator = -1;
            $columns = array(++$iterator => 'name',
                ++$iterator => 'username'
            );
            if (Gate::allows('users.viewPermissions'))
                $columns += array(++$iterator => 'id');
            if (Gate::allows('deniedPermissions.view'))
                $columns += array(++$iterator => 'id');
            if (Gate::allows('users.update'))
                $columns += array(++$iterator => 'id');
            if (Gate::allows('users.delete'))
                $columns += array(++$iterator => 'id');
            if (Gate::allows('roles.view'))
                $columns += array(++$iterator => 'id');
            $columns += array(
                ++$iterator => 'email',
            );
            $totalData = User::count();
            $totalFiltererd = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            if(empty($request->input('search.value')))
                $users = User::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            else{
                $search = $request->input('search.value');
                $data = User::where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('username', 'LIKE', '%'.$search.'%')
                    ->orWhere('id', 'LIKE', '%'.$search.'%')
                    ->orWhere('cpf', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%');
                $users = $data->get();
                $totalFiltererd = $data->count();
            }
            $data = array();
            if(!empty($users)){
                foreach ($users as $user){
                    $updateRoute = route('users.edit', $user->id);
                    $rolesRoute = route('roles.show', $user->id);
                    $permissionsRoute = route('users.permissions', $user->id);
                    $deniedPermissionsRoute = route('denied-permissions.show', $user->id);
                    $token = csrf_token();
                    $nestedData['Nome'] = $user->name;
                    $nestedData['Username'] = $user->username;
                    if (Gate::allows('users.viewPermissions'))
                        $nestedData['Listar Permissões'] = "&emsp;<a href='{$permissionsRoute}'>
                                                                        <button class='btn btn-sm btn-warning deniedPermissions'>
                                                                            <i class='fa fa-tags'></i>
                                                                        </button>
                                                                  </a>";
                    if (Gate::allows('deniedPermissions.view'))
                        $nestedData['Permissões bloqueadas'] = "&emsp;<a href='{$deniedPermissionsRoute}'>
                                                                        <button class='btn btn-sm btn-secondary permissions'>
                                                                            <i class='fa fa-times'></i>
                                                                        </button>
                                                                  </a>";
                    if (Gate::allows('users.update'))
                        $nestedData['Editar'] = "&emsp;<a href='{$updateRoute}'>
                                                            <button class='btn btn-sm btn-primary'>
                                                                <i class='fa fa-edit'></i>
                                                            </button>
                                                        </a>";
                    if (Gate::allows('users.delete'))
                        $nestedData['Remover'] = "&emsp;<button class='btn btn-sm btn-danger delete' 
                                                                data-id='{$user->id}' value='{$token}'
                                                                data-route='users'
                                                                data-type='usuário'
                                                                data-name='{$user->name}'
                                                                data-gender='o'
                                                                type='button' id='delete-customer-{$user->id}'
                                                                name='delete'>
                                                             <i class='fa fa-trash'></i>
                                                        </button>";
                    if(Gate::allows('roles.view'))
                        $nestedData['Ver Grupos'] = "&emsp;<a href='{$rolesRoute}'>
                                                             <button class='btn btn-sm btn-default roles' 
                                                                type='button' id='roles-{$user->id}'
                                                                name='roles'>
                                                             <i class='fa fa-users'></i>
                                                        </button>
                                                        </a>";
                    $nestedData['Email'] = $user->email;
                    $data[] = $nestedData;
                }
            }
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltererd),
                "data" => $data,
            );
            return response(json_encode($json_data), Response::HTTP_OK);
        }catch (\Exception $exception){
            return response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
