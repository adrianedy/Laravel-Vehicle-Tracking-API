<div class="modal fade" id="delete-confirmation" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="delete-confirm-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h4>Delete?</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this data?</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                    <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
