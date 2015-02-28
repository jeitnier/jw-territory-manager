<div class="modal fade" id="email-modal" tabindex="-1" role="dialog" aria-labelledby="email" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Email Territory Card: {{ $territory->label }}</h4>
            </div>
            <div class="modal-body">
                <p>Enter Email Address:</p>

                <div class="col-xs-6 col-xs-offset-3 text-center">
                    <input type="email" id="email" name="email" class="form-control input-lg">
                </div>
                <div class="clearfix"></div>
                <br>
                <p>
                    <em><strong>NOTE:</strong>
                        The SMTP email service does not always send emails immediately.
                        Inform the publisher it may take up to 5-10 minutes to receive an email.
                    </em>
                </p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button id="submit" type="submit" class="btn btn-primary btn-lg" data-territory-id="{{ $territory->id }}"><i class="fa fa-send"></i> Send</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->