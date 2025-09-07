<x-guest-layout>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Login da sua conta</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Endereço de Email -->
                <div class="mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="seuemail@mail.com" value="{{ old('email') }}" required autofocus
                        autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Palavra-passe -->
                <div class="mb-2">
                    <label class="form-label">
                        Senha
                        <!-- Alteração: Mensagem para procurar o admin -->

                    </label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Sua senha" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        <span class="form-label-description">
                            Esqueceu a senha? Procure o administrador na UTINFO.
                        </span>
                    @enderror
                </div>

                <!-- Lembrar-me -->
                <div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" />
                        <span class="form-check-label">Lembrar-me</span>
                    </label>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Log in') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Alteração: Link de registo removido -->
</x-guest-layout>
