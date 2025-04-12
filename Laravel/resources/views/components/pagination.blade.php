@props(['paginator', 'perPageOptions' => [1, 2, 3, 5, 10]])

<div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" action="{{ request()->url() }}" class="form-inline">
            <label for="perPage" class="mr-2">Items per page:</label>
            <select name="perPage" id="perPage" class="form-control" onchange="this.form.submit()">
                @foreach($perPageOptions as $value)
                    <option value="{{ $value }}" {{ $paginator->perPage() == $value ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @foreach(request()->except('perPage', 'page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>
</div>
<div class="d-flex justify-content-center">
    {{ $paginator->appends(['perPage' => $paginator->perPage()])->links('pagination::bootstrap-4') }}
</div>