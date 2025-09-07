@extends('admin.layouts.app')

@section('title', 'Editar Usuário')
@section('page-title', 'Editar Usuário: ' . $user->name)

@section('content')
<form action="{{ route('users.update', $user) }}" method="POST" class="card">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nova Senha</label>
            <input type="password" class="form-control" name="password">
            <small class="form-hint">Deixe em branco para não alterar a senha.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmar Nova Senha</label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>
        <div class="mb-3">
            <label class="form-label">Papel (Role)</label>
            <select name="role" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('users.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
    </div>
</form>
@endsection