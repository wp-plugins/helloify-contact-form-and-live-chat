<div class="wrap">
	<h2>Helloify Settings</h2>
	<div class="email-errors">
		<?php
			if (get_option('web_customer_email_valid') == 1) {
				echo '<p class="update-nag">Congratulations, Helloify is now set up on your site. Visit your homepage to see it in action. The button will be displayed in the bottom right hand corner.</p>';
			} else {
				echo '<p class="update-nag">Almost there! Helloify is live on your site and will work as a contact form. However to use all of the features including live chat, visit your homepage, look for the Helloify widget in the bottom right hand corner and click the pencil icon to finish the setup. </p>';
			}
		?>
	</div>
	<form id="sub-form" method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<?php settings_fields('helloify'); ?>

		<table class="form-table" width="100%">
			<tr>
				<td colspan="2">
					<p>Enter your email below and click "Set up Helloify"</p>
				</td>
			</tr>
			<tr valign="top">
				<td scope="row">Email:</td>
				<td>
					<input class="autoid regular-text ltr" type="text" name="web_customer_email" value="<?php echo get_option('web_customer_email'); ?>" />
				</td>
			</tr>
				<input type="hidden" class="autocid" name="web_customer_id" value="<?php echo get_option('web_customer_id'); ?>" />
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" class="ve" name="web_customer_email_valid" value="0" />
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Set up Helloify') ?>" />
		</p>

	</form>
</div>
<script>
jQuery(document).ready(function() {

	(function (window, document, $, undefined) {

		function isValidEmailAddress(c) {
			if (/^[\-!#$%&'*+\\.\/0-9=?A-Z\^_`a-z{|}~]+@[\-!#$%&'*+\\\/0-9=?A-Z\^_`a-z{|}~]+\.[\-!#$%&'*+\\.\/0-9=?A-Z\^_`a-z{|}~]+$/i.test(c)) {
				return true;
			}
			return false;
		}

		var subform = $('#sub-form'),
			errors = $('.email-errors'),
			autoid = $('.autoid').val();

		if (autoid !== '' && isValidEmailAddress(autoid)) {
			$.ajax({
				url: 'https://dashboard.helloify.com/chat/plugins/billing/api/account/code/' + encodeURIComponent(autoid),
				success: function(data, textStatus, jqXHR) {
					if (data.installed !== undefined && data.installed) {
						errors.html('<p class="update-nag">Congratulations, Helloify is now set up on your site. You can signin at <a href="https://dashboard.helloify.com" target="_blank">https://dashboard.helloify.com</a> to edit your settings and start chatting.</p>').show();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					errors.show();
				},
				dataType: 'jsonp'
			});
		} else {
			errors.show();
		}

		subform.submit(function(e) {
			e.preventDefault();

			autoid = $('.autoid').val();
			if (autoid !== '' && isValidEmailAddress(autoid)) {
				$.ajax({
					url: 'https://dashboard.helloify.com/chat/plugins/billing/api/account/code/' + encodeURIComponent(autoid),
					success: function(data, textStatus, jqXHR) {
						if (data.guid !== undefined && data.guid.length == 36) {
							$('.autocid').val(data.guid);
							if (data.installed) {
								$('.ve').val('1');
							}
						}

						if (data.installed !== undefined && data.installed) {
							errors.html('<p class="update-nag">Congratulations, Helloify is now set up on your site. You can signin at <a href="https://dashboard.helloify.com" target="_blank">https://dashboard.helloify.com</a> to edit your settings and start chatting.</p>').show();
						}

						subform.off('submit');
						subform.submit();
					},
					error: function(jqXHR, textStatus, errorThrown) {},
					dataType: 'jsonp'
				});
			} else {
				errors.html('<p class="update-nag"><strong>' + autoid + '</strong> is an invalid email please check.</p>');
				return false;
			}
		});
	})(this, document, jQuery);
});
</script>
<style>
	.settings-error, .email-errors {
		display: none;
	}
</style>