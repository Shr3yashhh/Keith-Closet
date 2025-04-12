<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
   public function test_create_user()
   {

        $user = User::factory()->count(1)->create();
        if($user->count() > 0){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
   }

   public function test_update_user()
   {
        $user = User::factory()->count(1)->create();
        $user = User::first();
        if($user->count() > 0){
            $user->name = 'New Name';
            $user->save();
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
   }

   public function test_delete_user()
   {
        $user = User::factory()->count(1)->create();
        $user = User::first();
        if($user->count() > 0){
            $user->delete();
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
   }

    public function test_get_user()
    {
          $user = User::factory()->count(1)->create();
          $user = User::first();
          if($user->count() > 0){
                $this->assertTrue(true);
          }else{
                $this->assertTrue(false);
          }
    }

    public function test_get_all_users()
    {
          $user = User::factory()->count(1)->create();
          $user = User::all();
          if($user->count() > 0){
                $this->assertTrue(true);
          }else{
                $this->assertTrue(false);
          }
    }

    public function test_get_post_by_id()
    {
        $post = User::factory()->count(1)->create();
        $post = User::first();
        $post = User::findOrFail($post->id);
        if($post->count() > 0){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }

}
