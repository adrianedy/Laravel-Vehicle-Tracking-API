<div class="modal fade" tabindex="-1" role="dialog" id="modal-edit-device">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="ajax-form" enctype="multipart/form-data" method="post">
        @csrf
        @method('patch')
        <div class="modal-header">
          <h5 class="modal-title">Edit Device</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="required">Name</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="form-group">
            <label class="required">License</label>
            <input type="text" name="license" class="form-control">
          </div>
          <div class="form-group">
            <label class="required">Type</label>
            <select name="type" class="form-control">
              <option value="2">2 wheeler</option>
              <option value="4">4 wheeler</option>
            </select>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button class="btn btn-save btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
