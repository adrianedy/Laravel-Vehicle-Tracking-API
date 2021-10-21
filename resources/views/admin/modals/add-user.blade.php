<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-user">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="ajax-form" enctype="multipart/form-data" method="post" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add User</h5>
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
            <label class="required">Email</label>
            <input type="text" name="email" class="form-control">
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label class="required">Phone Code</label>
              <input type="text" class="form-control mb-1 phone" name="phone_code">
            </div>
            <div class="form-group col-md-9">
              <label class="required">Phone</label>
              <input type="text" class="form-control mb-1 phone" name="phone_number">
            </div>
          </div>
          {{-- <div class="form-row">
            <div class="form-group col-md-6">
              <label class="required">Password</label>
              <input type="password" class="form-control pwstrength mb-1" data-indicator="pwindicator" name="password">
              <div id="pwindicator" class="pwindicator">
                <div class="bar"></div>
                <div class="label"></div>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label class="required">Confirm Password</label>
              <input type="password" class="form-control pwstrength mb-1" data-indicator="pwindicator2" name="password_confirmation">
              <div id="pwindicator2" class="pwindicator">
                <div class="bar"></div>
                <div class="label"></div>
              </div>
            </div>
          </div> --}}
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="required">PIN</label>
              <input type="password" class="form-control mb-1 pin" name="pin" maxlength="5">
            </div>
            <div class="form-group col-md-6">
              <label class="required">Confirm PIN</label>
              <input type="password" class="form-control mb-1 pin" name="pin_confirmation" maxlength="5">
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="active" id="addUserActive" checked>
              <label class="custom-control-label" for="addUserActive">Active</label>
            </div>
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
