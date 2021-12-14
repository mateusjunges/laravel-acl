<?php

namespace Junges\ACL\Exceptions\Solutions\Utilities;

use Junges\ACL\Concerns\ACLWildcardsTrait;
use Junges\ACL\Concerns\GroupsTrait;
use Junges\ACL\Concerns\HasGroups;
use Junges\ACL\Concerns\HasPermissions;

class InsertMissingTrait
{
    private const TRAITS = [
        'UsersTrait' => HasPermissions::class,
        'ACLWildcardsTrait' => ACLWildcardsTrait::class,
        'GroupsTrait' => GroupsTrait::class,
        'PermissionsTrait' => HasGroups::class,
    ];

    /**
     * Replace the missing trait in the given class with given trait.
     */
    public static function insert($class, $trait)
    {
        $userClass = $class;
        $composer = require base_path('/vendor/autoload.php');

        $classes = array_filter($composer->getClassMap(), function ($class) use ($userClass) {
            if (\Illuminate\Support\Str::contains($class, 'User')) {
                return $class === $userClass;
            }

            return false;
        }, ARRAY_FILTER_USE_KEY);

        $originalFile = file_get_contents($classes[$userClass]);
        $newFile = preg_replace('/use /', 'use '.self::TRAITS[$trait].";\nuse ", $originalFile, 1);
        $newFile = preg_replace("/{\n/", "{\n".'    use '.$trait.";\n", $newFile, 1);

        file_put_contents($classes[$userClass], $newFile);
    }
}
