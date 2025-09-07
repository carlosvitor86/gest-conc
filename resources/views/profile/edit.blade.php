@extends('admin.layouts.app')

@section('title', 'Perfil do Utilizador')
@section('page-title', 'Editar Perfil')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        @include('profile.partials.update-profile-information-form')
    </div>
    <div class="col-lg-6">
        @include('profile.partials.update-password-form')
    </div>
</div>

<!-- Success Modal -->
<div class="modal modal-blur fade" id="success-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-success"></div>
      <div class="modal-body text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg>
        <h3>Sucesso</h3>
        <div class="text-muted">As suas alterações foram salvas. Você será redirecionado.</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se existe uma sessão de status de sucesso
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        // Cria uma instância do modal
        var successModal = new bootstrap.Modal(document.getElementById('success-modal'));
        
        // Mostra o modal
        successModal.show();

        // Define um temporizador para redirecionar após 2 segundos
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}";
        }, 2000);
    @endif
});
</script>
@endpush