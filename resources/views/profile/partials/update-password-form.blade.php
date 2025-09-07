<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Atualizar Senha') }}</h3>
    </div>
    <div class="card-body">
        <p class="card-subtitle">{{ __('Garanta que a sua conta utiliza uma senha longa e aleatória para se manter segura.') }}</p>
        <form method="post" action="{{ route('password.update') }}" class="mt-3">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="current_password" class="form-label">{{ __('Senha Atual') }}</label>
                <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Nova Senha') }}</label>
                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirmar Nova Senha') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-4">
                <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                <!-- Mensagem de confirmação de texto removida -->
            </div>
        </form>
    </div>
</div>