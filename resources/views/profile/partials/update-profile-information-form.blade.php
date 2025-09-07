<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Informação do Perfil') }}</h3>
    </div>
    <div class="card-body">
        <p class="card-subtitle">{{ __("Atualize a informação de perfil e o endereço de email da sua conta.") }}</p>
        <form method="post" action="{{ route('profile.update') }}" class="mt-3">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Nome') }}</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-muted">
                            {{ __('O seu endereço de email não foi verificado.') }}
                            <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">{{ __('Clique aqui para reenviar o email de verificação.') }}</button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green">
                                {{ __('Um novo link de verificação foi enviado para o seu endereço de email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-4">
                <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                <!-- Mensagem de confirmação de texto removida -->
            </div>
        </form>
    </div>
</div>
