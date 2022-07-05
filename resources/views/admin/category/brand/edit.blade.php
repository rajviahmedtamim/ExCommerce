<form action="{{ route('brand.update') }}" method="Post" id="add-form" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="brand_name">Brand Name</label>
            <input type="text" class="form-control" name="brand_name" value="{{ $data->brand_name }}">
            <small id="emailHelp" class="form-text text-muted">This is your Brand</small>
        </div>
        <input type="hidden" name="id" value="{{ $data->id }}">
        <div class="form-group">
            <label for="brand_name">Brand Logo</label>
            <input type="file" class="dropify" data-height="140" id="input-file-now" name="brand_logo" >
            <input type="hidden" name="old_logo" value="{{ $data->brand_logo }}">
        </div>
    </div>
    <div class="modal-footer">
        <button type="Submit" class="btn btn-primary"><span  class="d-none">loading.....</span>Update</button>
    </div>
</form>

