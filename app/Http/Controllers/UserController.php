<?php

namespace App\Http\Controllers;

use App\OffsiteUser;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {


        $user = User::where("name", $username)->first();

        if(! $user){
            $user = OffsiteUser::where("name", $username)->first();
            if(! $user){
                $user = (object) ["name" => $username, "rank_id" => 0, "perm_id" => 0, "email" => null, "email_verified_at" => null, "created_at" => null, "updated_at" => null];
            }
        }

        return view("user", ["user" => $user]);
    }

    public function search()
    {
        $request = request()->get("naam");
        return redirect("/user/$request");

    }

    public function update(User $username)
    {

        switch(request()->get("changeval")){

            case "promote":
                $this->promote($username);
                return redirect("/user/" . $username->name);

            case "demote":
                $this->demote($username);
                return redirect("/user/" . $username->name);

            case "ontslag":
                $this->ontslagen($username);
                return redirect("/user/" . $username->name);

        }
    }

    public function promote(User $user)
    {
        User::where("name", $user->name)->update(["rank_id" => $user->rank_id + 1]);

    }

    public function demote(User $user)
    {
        User::where("name", $user->name)->update(["rank_id" => $user->rank_id - 1]);
    }


    public function ontslagen(User $user)
    {
        User::where("name", $user->name)->update(["rank_id" => -1]);
    }

    public function delete(User $user)
    {

    }



}
