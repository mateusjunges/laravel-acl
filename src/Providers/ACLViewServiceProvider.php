<?php

namespace Junges\ACL\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ACLViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('group', function ($arguments) {
                list($group, $guard) = explode(',', $arguments.',');
                
                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasGroup({$group})){?>";
            });

            $bladeCompiler->directive('elsegroup', function ($arguments) {
                list($group, $guard) = explode(',', $arguments.',');
                
                return "<?php }else if(auth({$guard})->check() && auth({$guard})->user()->hasGroup({$group})){?>";
            });

            $bladeCompiler->directive('endgroup', function () {
                return '<?php } ?>';
            });

            $bladeCompiler->directive('permission', function ($arguments) {
                list($permission, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasPermission({$permission})){?>";
            });

            $bladeCompiler->directive('elsepermission', function ($arguments) {
                list($permission, $guard) = explode(',', $arguments.',');

                return "<?php }else if(auth({$guard})->check() && auth({$guard})->user()->hasPermission({$permission})){?>";
            });

            $bladeCompiler->directive('endpermission', function () {
                return '<?php } ?>';
            });
        });
    }
}
