@extends("layouts.banner")

    @section("menu")

        <div class="profile">


            <table class="table table-striped table-dark table-bordered">
                <th colspan="3" class="nametable"> {{ $user->name }} </th>
                <tr>
                    <th scope="row">Rank</th>
                </tr>
                <tr>
                    <td>{{ App\Rank::where("rank_id", $user->rank_id)->first()->rank_name }}</td>
                </tr>
            </table>

            <form method="POST" name="change">
                @csrf
                @method("PUT")

                <div class="changers">
                    <button name="changeval" value="promote" id="promote">Promoveren</button>
                    <button name="changeval" value="demote" id="demote">Degraderen</button>
                    <button name="changeval" value="ontslag" id="ontslag">Ontslagen</button>
                    <button name="changeval" value="custom" id="custom">Custom</button>
                </div>
                <br>
                <input type="text" name="reason" placeholder="Reden" required>
            </form>

        </div>

    @endsection
