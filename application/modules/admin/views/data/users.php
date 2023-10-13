<?php
defined('BASEPATH') or exit('No direct script access allowed');


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">

            <div class="row">

                <form action="<?= base_url('admin/datatables/users/import') ?>" id="form-import" method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <div class="input-group-prepend">

                            <button class="custom-btn btn-warning" type="submit" id="inputGroupFileAddon03"> <i class="fas fa-fw fa-download"></i>
                            </button>
                        </div>
                        <div class="custom-file">
                            <label class="custom-file-label" for="inputGroupFile03">Import file</label>
                            <input type="file" name="fileusers" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                        </div>

                    </div>
                </form>

            </div>

        </div>
        <div class="col-md-12">
            <div class="mt-2">
                <table class="table table-sm table-bordered user-table" id="table-example3">
                    <thead class="">
                        <tr>
                            <th><input class="check-all-trig" type='checkbox' id=''></th>
                            <th>No</th>
                            <th>{lang_full_name}</th>
                            <th>NIP/NIK</th>
                            <th>{lang_role}</th>
                            <th>{lang_status}</th>
                            <th>{lang_actions}</th>
                        </tr>
                    </thead>
                    <tbody class="">

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalusers" tabindex="-1" aria-labelledby="modaluserslabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaluserslabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form">
                    <div class="form-group row">
                        <input class="form-control" type="hidden" id="id" name="id" />

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="full_name">Fullname and ect.</label>
                            <input class="form-control" type="text" id="full_name" name="full_name" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="nip_or_nik">NIP/NIK</label>
                            <input class="form-control" type="nip_or_nik" id="nip_or_nik" name="nip_or_nik" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password" />
                            <div class="form-control-feedback text-danger">If want change password</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="password_confirm">Confirm Password</label>
                            <input class="form-control" type="password" id="password_confirm" name="password_confirm" />
                            <div class="form-control-feedback text-danger">If want change password</div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?= $this->load->view('admin/datatables/users'); ?>