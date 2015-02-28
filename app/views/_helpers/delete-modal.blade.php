<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="delete">{{ $title }}</h4>
			</div>
			<div class="modal-body">
				{{ $body }}
			</div>
			<div class="modal-footer">
				<button id="submit" type="submit" class="btn btn-primary" data-id="{{ $id }}" data-route="{{ $route }}" data-redirect="{{ $redirect }}" data-type="{{ ( ! empty($type)) ? $type : '' }}">{{ $button_text }}</button>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->