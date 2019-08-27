<style>.sudoSu__interface .select_user{max-width:150px}.sudoSu__interface--hasSudoed .select_user{width:100%;max-width:unset}.sudoSu{position:fixed;right:20px;bottom:10px;z-index:99999}.sudoSu__btn{font-size:1.5em;background:#333;width:30px;height:30px;line-height:30px;color:#fff;text-align:center;border-radius:50%}.sudoSu__btn--hasSudoed{background:#00b067}.sudoSu__interface{position:absolute;right:36px;bottom:3px}.sudoSu__interface--hasSudoed{background:#fff;padding:20px;bottom:0;border:1px solid #cecece;border-radius:5px;box-shadow:0 1px 1px rgba(0,0,0,.1);width:350px}.sudoSu__infoLine{margin-bottom:5px}.sudoSu__infoLine>span{font-weight:700}.sudoSu__resetBtn{-webkit-appearance:none;-moz-appearance:none;cursor:pointer;border:0;margin:5px 0 15px 0;padding:6px 10px;background:#00b067;color:#fff;border-radius:4px;border-bottom:2px solid #088b54}.sudoSu__resetBtn:hover{background:#088b54}.hidden{display:none}</style>

<div class="sudoSu" title="Sudo Su">
    <div class="sudoSu__btn {{ $hasSudoed ? 'sudoSu__btn--hasSudoed' : '' }}" id="sudosu-js-btn">
        <i class="fa fa-user-secret" aria-hidden="true"></i>
    </div>

    <div class="sudoSu__interface {{ $hasSudoed ? 'sudoSu__interface--hasSudoed' : '' }} hidden"
         id="sudosu-js-interface">
        @if ($hasSudoed)
            <div class="sudoSu__infoLine">
                You are using account: <span>{{ $currentUser->{config('faker_user.fields', 'name')} }}</span>
            </div>

            @if ($originalUser)
                <div class="sudoSu__infoLine">
                    You are logged in as: <span>{{ $originalUser->{config('faker_user.fields', 'name')} }}</span>
                </div>
            @endif

            <form action="{{ route('fakerUser.return') }}" method="post">
                {!! csrf_field() !!}
                <input type="submit" class="sudoSu__resetBtn"
                       value="{{ $originalUser ? 'Return to original user' : 'Log out' }}">
            </form>
        @endif

        <form action="{{ route('fakerUser.login_as_user') }}" method="post">
            <select name="userId" onchange="this.form.submit()" class="select_user">
                <option disabled selected>Faker User</option>
                @foreach (@$usersFakers as $user)
                    <option value="{{ $user->id }}">{{ @$user->{config('faker_user.fields', 'name')} }}</option>
                @endforeach
            </select>

            {!! csrf_field() !!}

            <input type="hidden" name="originalUserId" value="{{ @$originalUser->id ?: null }}">
        </form>
    </div>
</div>

<script>const btn=document.getElementById('sudosu-js-btn'); const element=document.getElementById('sudosu-js-interface'); btn.addEventListener('click', function () {element.classList.toggle('hidden')});</script>
