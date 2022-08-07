
    <form action="{{ route('warehouse.update') }}" method="Post" id="add-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="category_name">Warehouse Name</label>
                <input type="text" class="form-control" name="warehouse_name" required="" value="{{ $warehouse->warehouse_name }}">
            </div>
            <input type="hidden" name="id" value="{{ $warehouse->id }}">
            <div class="form-group">
                <label for="category_name">Address</label>
                <input type="text" class="form-control" name="warehouse_address" required="" value="{{ $warehouse->warehouse_address }}">
            </div>
            <div class="form-group">
                <label for="category_name">Phone</label>
                <input type="number" class="form-control" name="warehouse_phone" required="" value="{{ $warehouse->warehouse_phone }}">
            </div>
        </div>
        <div class="modal-footer">
            <button type="Submit" class="btn btn-primary"><span  class="d-none loader"><i class="fas fa-spinner"></i>loading.....</span><span class="submit_btn">Submit</span></button>
        </div>
    </form>

