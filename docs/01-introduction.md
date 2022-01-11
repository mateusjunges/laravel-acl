---
title: Introduction
weight: 1
---

This package allows you to manage model permissions and groups.

```php
$user->assignPermission('edit posts');

$user->assignGroup('manager');

$group->assignPermission('edit users')
```

You can also set permissions using multiple guards, since every guard has its own set of permissions and groups

### We have badges!

<section class="article_badges">
  <a href=""><img src="https://github.com/mateusjunges/laravel-acl/workflows/Continuous%20Integration/badge.svg" alt="Run tests"></a>
  <a href="https://packagist.org/packages/mateusjunges/laravel-acl"><img src="https://img.shields.io/packagist/v/mateusjunges/laravel-acl.svg?style=flat" alt="Latest version on packagist"></a>
  <a href="https://packagist.org/packages/mateusjunges/laravel-acl"><img src="https://img.shields.io/packagist/dt/mateusjunges/laravel-acl.svg?style=flat" alt="Total downloads"></a>
  <a href="https://github.com/mateusjunges/laravel-acl/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat" alt="License"></a>
  <a href="https://github.com/mateusjunges/laravel-acl/actions/workflows/php-cs-fixer.yml"><img src="https://github.com/mateusjunges/laravel-acl/actions/workflows/php-cs-fixer.yml/badge.svg" alt="Fix and styling"></a>
</section>
