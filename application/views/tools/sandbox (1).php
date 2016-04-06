
<!-- Load style sandbox -->
<link href="<?= base_url('assets/css/tools/sandbox/sandbox.css') ?>" rel="stylesheet" type="text/css" /> 
<!-- Load utilities Js -->
<script src="<?= base_url('assets/js/tools/sandbox/utilities.js') ?>" type="text/javascript" charset="utf-8"></script>
<!-- Load Config Js -->
<script src="<?= base_url('assets/libs/ace/custom/config.js') ?>" type="text/javascript" charset="utf-8"></script>
<!-- Load Config css -->
<link href="<?= base_url('assets/libs/ace/custom/config.css') ?>" rel="stylesheet" type="text/css" /> 
<!-- Load Rumble effect -->
<script type="text/javascript" src="<?= base_url('assets/js/jquery.jrumble.1.3.min.js') ?>"></script>

<div>

	<form id="form_sandbox" name="form_sandbox" method="POST" target="_self" action="<?=base_url('search')?>">

		<br />
		<p>

			<h1></h1>
			<br /><br />
			<div style="width:50%" class="">
				<br/><br/><br/>

				<textarea style="display:none" id="request" name="search_text"></textarea>

				<div id="container_editor">
					<div class="actions">
						<a id="execute" class="classify fleft"><i class="icon-repeat"></i> Execute Request</a>
						
						<!--<a id="format" class="classify fleft">(format test)</a>-->
					</div>

					<div id="editor"><?= $request ?></div>
				</div>

				<input type="hidden" id="prefix_search_text" name="prefix_search_text" value="<?=LINKBREAKERS_SANDBOX_REFRESH?>">

				<br />
			</div>
			<br /><br />

			<br /><br />
			
			<div style="width:50%" class="result">

				<p><?=$result?></p>
			</div>
			<br /><br />

		</p>
		<br />

	</form>

</div>

<!-- Load Ace component -->
<script src="<?= base_url('assets/libs/ace/src/ace.js') ?>" type="text/javascript" charset="utf-8"></script>
<!-- Load Main -->
<script src="<?= base_url('assets/libs/ace/custom/main.js') ?>" type="text/javascript" charset="utf-8"></script>

