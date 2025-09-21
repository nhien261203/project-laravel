<form action="{{ $action }}" method="POST" class="delete-form inline-block">
    @csrf
    @method('DELETE')
    <button type="button" class="btn-delete text-red-600 hover:cursor-pointer">
        xóa
    </button>
</form>
