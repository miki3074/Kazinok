<table class="table table-striped- table-bordered table-hover table-checkable" id="dtable5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Сумма</th>
            <td>Кошелек</td>
            <td>Система</td>
            <td>FAKE</td>
        </tr>
    </thead>
    <tbody>
    @foreach($wdws as $withdraw1)
        @if (\App\User::query()->find($withdraw1->user_id))
        <?php
            $user = \App\User::select('username', 'is_youtuber')->find($withdraw1->user_id);
        ?>
            <tr>
                <td>{{ $withdraw1->id }}</td>
                <td><a target="_blank" href="/admin/users/edit/{{$withdraw1->user_id}}">{{ $user->username }} @if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</a></td>
                <td>{{ intval($withdraw1->sum) }}</td>
                <td>{{ $withdraw1->wallet }}</td>
                <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw1->system]['title'] }}</td>
                <td>@if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>