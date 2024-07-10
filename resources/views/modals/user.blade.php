<form id="form">
    <input type="hidden" name="Id" value="{{$user['id']}}" />
    <input type="hidden" name="Date" value="{{$user['date']}}" />
    <input type="hidden" name="Github" value="https://github.com/elblood21" />
    <div class="mt-3">
        <label for="codigo">Codigo</label>
        <select class="form-control" name="Code" id="codigo">
            <option value="" disabled selected>Selecciona un usuario</option>
            @foreach($usersCode as $u)
                <option value="{{$u['code']}}" @if($user && $user['code'] == $u['code']) selected @endif> {{$u['name']}} </option>
            @endforeach
        </select>
    </div>
    <div class="mt-3">
        <label for="monto">Monto</label>
        <input type="text" class="form-control" id="monto" name="Amount" value="{{$user['amount']}}" />
    </div>
</form>