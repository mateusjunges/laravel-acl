<?php

namespace Junges\ACL\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ACLViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('group', function ($group) {
            return "<?php if(auth()->check() && auth()->user()->hasGroup({$group})){?>";
        });

        Blade::directive('elsegroup', function ($group) {
            return "<?php }else if(auth()->check() && auth()->user()->hasGroup({$group})){?>";
        });

        Blade::directive('endgroup', function () {
            return '<?php } ?>';
        });

        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})){?>";
        });

        Blade::directive('elsepermission', function ($permission) {
            return "<?php }else if(auth()->check() && auth()->user()->hasPermission({$permission})){?>";
        });

        Blade::directive('endpermission', function () {
            return '<?php } ?>';
        });

        Blade::directive('allpermission', function ($permissions) {
            return "<?php if(auth()->check() && auth()->user()->hasAllPermissions({$permissions})){?>";
        });

        Blade::directive('endallpermission', function () {
            return '<?php } ?>';
        });

        Blade::directive('anypermission', function ($permissions) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyPermission({$permissions})){?>";
        });

        Blade::directive('endanypermission', function () {
            return '<?php } ?>';
        });

        Blade::directive('anygroup', function ($groups) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyGroup({$groups})){?>";
        });

        Blade::directive('endanygroup', function () {
            return '<?php } ?>';
        });

        Blade::directive('allgroups', function ($groups) {
            return "<?php if(auth()->check() && auth()->user()->hasAllGroups({$groups})){?>";
        });

        Blade::directive('endallgroups', function () {
            return '<?php } ?>';
        });
    }
}
