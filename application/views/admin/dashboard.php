<div id="jumbotron" class="jumbotron">


	<div id="block-space" class="container block-space">

		<!-- Spacer -->
		<div class="pspacer"></div>

		<div class="container">

			<!-- Logo -->
			<a href="#" data-transition-href="<?= base_url() ?>">
				<img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
			</a>

			<!-- Punchline -->
			<h1 id="punchline" class="punchline">Dashboard</h1>

			<div class="pspacer-medium"></div>

			<div class="well">
			
			<h1>Statistics</h1>
			<hr>

			<h3>There are <strong><?=$stats_num_results?> results</strong> for <strong><?=$stats_num_users?> users</strong>.</h3>
			<div class="pspacer-little"></div>
			The last entry was created on <strong><?= date('d/m/Y H:i:s', $stats_last_entry['date']) ?></strong> (#<?=$stats_last_entry['id']?> <em>"<?=$stats_last_entry['autocomplete']?>"</em>).
			<div class="pspacer-little"></div>
			The last user was registered on <strong><?= date('d/m/Y H:i:s', $stats_last_user['date_subscribe']) ?></strong> (#<?=$stats_last_user['id']?> <em>"<?=$stats_last_user['username']?>"</em>)
			<div class="pspacer-little"></div>

			<button type="submit" class="btn btn-primary">
        	<i class="icon-signal"></i> Other statistics
      		</button>


			</div>

			<div class="pspacer-medium"></div>

			<div class="well">
			
			<h1>Results</h1>
			<hr>

			<table class="table table-hover">

		        <thead>
		          <tr>
		            <th>#</th>
		            <th>First Name</th>
		            <th>Last Name</th>
		            <th>Username</th>
		          </tr>
		        </thead>

		        <tbody>

		          <tr>
		            <td>1</td>
		            <td>Mark</td>
		            <td>Otto</td>
		            <td>@mdo</td>
		          </tr>
		          <tr>
		            <td>2</td>
		            <td>Jacob</td>
		            <td>Thornton</td>
		            <td>@fat</td>
		          </tr>

		        </tbody>

		     </table>

			<div class="pspacer-little"></div>

			<button type="submit" class="btn btn-primary">
        	<i class="icon-th"></i> More results
      		</button>


			</div>

			<div class="pspacer-medium"></div>

</div>

</div>

</div>