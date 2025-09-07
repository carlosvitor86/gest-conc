@extends('admin.layouts.app')

@section('title', 'Novo Usuário')
@section('page-title', 'Cadastrar Novo Usuário')

@section('content')
<form action="{{ route('users.store') }}" method="POST" class="card">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Papel (Role)</label>
            <select name="role" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('users.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar Usuário</button>
    </div>
</form>
@endsection