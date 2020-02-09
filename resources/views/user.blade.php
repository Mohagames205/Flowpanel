@extends("layouts.banner")

    @section("menu")

        <div class="profile">


            <table class="table table-striped table-dark table-bordered">
                <th colspan="3" class="nametable"> {{ $user->name }}</th>
                <tr>
                    <th scope="row">Rank</th>

                </tr>
                <tr>
                    <td>{{ $user->rank_id }}</td>
                </tr>
            </table>

        </div>

    @endsection
