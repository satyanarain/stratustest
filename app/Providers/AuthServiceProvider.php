<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Repositories\Util\AclPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\User'  => 'App\Repositories\Util\AclPolicy'
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);


        foreach (get_class_methods(new AclPolicy()) as $method) {
            $gate->define($method, "App\Repositories\Util\AclPolicy@{$method}");
        }

        // $gate->define('update-user', function ($user, $post) {
        //     // echo '<pre>';
        //     // print_r($post->id);
        //     // print_r($post->user_id);
        //     // die();
        //     return $post->id == $post->userid; 
        // });

        // $gate->define('user-admin-access', function ($user, $post) {
        //     if($post->id == $post->userid || $post->role == 'admin'){
        //         return true; 
        //     }
        // });

        // $gate->define('admin-access', function ($user, $post) {
        //     return $post->role == 'admin';
        // });

        
    }
}
